<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Exception;
use Icepay_Api_Webservice;
use Icepay_Basicmode;
use Icepay_Paymentmethod_Creditcard;
use Icepay_Paymentmethod_Ddebit;
use Icepay_Paymentmethod_Directebank;
use Icepay_Paymentmethod_Ideal;
use Icepay_Paymentmethod_Mistercash;
use Icepay_PaymentObject;
use Icepay_Postback;
use Icepay_Result;
use Icepay_StatusCode;
use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethod;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Core\Server;
use Pronamic\WordPress\Pay\Fields\CachedCallbackOptions;
use Pronamic\WordPress\Pay\Fields\IDealIssuerSelectField;
use Pronamic\WordPress\Pay\Fields\SelectField;
use Pronamic\WordPress\Pay\Fields\SelectFieldOption;
use Pronamic\WordPress\Pay\Fields\SelectFieldOptionGroup;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Payments\Payment;
use WP_Error;

/**
 * Title: ICEPAY gateway
 * Description:
 * Copyright: 2005-2023 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.6
 * @since 1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Config
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Construct and initialize an ICEPAY gateway
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct();

		$this->config = $config;

		// Default properties for this gateway.
		$this->set_method( self::METHOD_HTTP_REDIRECT );

		// Supported features.
		$this->supports = [];

		// Payment method iDEAL.
		$ideal_payment_method = new PaymentMethod( PaymentMethods::IDEAL );

		$ideal_issuer_field = new IDealIssuerSelectField( 'ideal-issuer' );

		$ideal_issuer_field->set_options(
			new CachedCallbackOptions(
				function() {
					return $this->get_ideal_issuers();
				},
				'pronamic_pay_ideal_issuers_' . \md5( \wp_json_encode( $config ) )
			)
		);

		$ideal_payment_method->add_field( $ideal_issuer_field );

		// Payment method credit card.
		$credit_card_payment_method = new PaymentMethod( PaymentMethods::CREDIT_CARD );

		$credit_card_issuer_field = new SelectField( 'credit-card-issuer' );
		$credit_card_issuer_field->set_label( __( 'Brand', 'pronamic_ideal' ) );
		$credit_card_issuer_field->meta_key = 'credit_card_issuer';

		$credit_card_issuer_field->set_options(
			new CachedCallbackOptions(
				function() {
					return $this->get_credit_card_issuers();
				},
				'pronamic_pay_credit_card_issuers_' . \md5( \wp_json_encode( $config ) )
			)
		);

		$credit_card_payment_method->add_field( $credit_card_issuer_field );

		// Payment methods.
		$this->register_payment_method( $ideal_payment_method );
		$this->register_payment_method( $credit_card_payment_method );
		$this->register_payment_method( new PaymentMethod( PaymentMethods::DIRECT_DEBIT ) );
		$this->register_payment_method( new PaymentMethod( PaymentMethods::BANCONTACT ) );
		$this->register_payment_method( new PaymentMethod( PaymentMethods::SOFORT ) );
	}

	/**
	 * Get iDEAL issuers.
	 *
	 * @return iterable<SelectFieldOption|SelectFieldOptionGroup>
	 */
	private function get_ideal_issuers() {
		$methods = Icepay_Api_Webservice::getInstance()
			->paymentmethodService()
			->setMerchantID( $this->config->merchant_id )
			->setSecretCode( $this->config->secret_code )
			->retrieveAllPaymentmethods()
			->asArray();

		$ideal_methods = array_filter(
			$methods,
			function( $method ) {
				return is_array( $method ) && isset( $method['PaymentMethodCode'] ) && 'IDEAL' === $method['PaymentMethodCode'];
			}
		);

		if ( empty( $ideal_methods ) ) {
			return [];
		}

		$issuers = Icepay_Api_Webservice::getInstance()->singleMethod()
			->loadFromArray( $methods )
			->selectPaymentMethodByCode( 'IDEAL' )
			->getIssuers();

		$options = [];

		foreach ( $issuers as $issuer ) {
			$options[] = new SelectFieldOption( $issuer['IssuerKeyword'], $issuer['Description'] );
		}

		return $options;
	}

	/**
	 * Get credit card issuers.
	 *
	 * @return iterable<SelectFieldOption|SelectFieldOptionGroup>
	 */
	private function get_credit_card_issuers() {
		$issuers = [];

		$method = new Icepay_Paymentmethod_Creditcard();

		if ( isset( $method->_issuer ) ) {
			$issuers = $method->_issuer;
		}

		$options = [];

		foreach ( $issuers as $issuer ) {
			switch ( $issuer ) {
				case 'AMEX':
					$name = _x( 'AMEX', 'Payment method name', 'pronamic_ideal' );

					break;
				case 'MASTER':
					$name = _x( 'MASTER', 'Payment method name', 'pronamic_ideal' );

					break;
				case 'VISA':
					$name = _x( 'VISA', 'Payment method name', 'pronamic_ideal' );

					break;
				default:
					$name = $issuer;

					break;
			}

			$options[] = new SelectFieldOption( $issuer, $name );
		}

		return $options;
	}

	/**
	 * Start an transaction
	 *
	 * @see Core_Gateway::start()
	 *
	 * @param Payment $payment Payment.
	 */
	public function start( Payment $payment ) {
		/*
		 * Order ID
		 * Your unique order number.
		 * This can be auto incremental number from your payments table
		 *
		 * Data type  = String
		 * Max length = 10
		 * Required   = Yes
		 */

		// Locale, country and language.
		$locale   = get_locale();
		$language = substr( $locale, 0, 2 );
		$country  = strtoupper( substr( $locale, 3, 2 ) );

		$customer = $payment->get_customer();

		if ( null !== $customer ) {
			$locale = $customer->get_locale();

			if ( null !== $locale ) {
				$locale_parts = \explode( '_', $locale );

				// Locale not always contains `_`, e.g. "Nederlands" in Firefox.
				if ( count( $locale_parts ) > 1 ) {
					$country = $locale_parts[1];
				}
			}

			$language = strtoupper( (string) $customer->get_language() );
		}

		// Set country from billing address.
		$billing_address = $payment->get_billing_address();

		if ( null !== $billing_address ) {
			$country_code = $billing_address->get_country_code();

			if ( ! empty( $country_code ) ) {
				$country = $country_code;
			}
		}

		// Payment object.
		$payment_object = new Icepay_PaymentObject();
		$payment_object
			->setAmount( $payment->get_total_amount()->get_minor_units()->to_int() )
			->setCountry( $country )
			->setLanguage( $language )
			->setReference( $payment->get_order_id() )
			->setDescription( $payment->get_description() )
			->setCurrency( $payment->get_total_amount()->get_currency()->get_alphabetic_code() )
			->setIssuer( $payment->get_meta( 'issuer' ) )
			->setOrderID( $payment->format_string( $this->config->order_id ) );

		/*
		 * Payment method
		 * @since 1.2.0
		 */
		$icepay_method = null;

		switch ( $payment->get_payment_method() ) {
			case PaymentMethods::CREDIT_CARD:
				// @link https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/creditcard.php
				$icepay_method = new Icepay_Paymentmethod_Creditcard();

				$payment_object->setIssuer( $payment->get_meta( 'credit_card_issuer' ) );

				break;
			case PaymentMethods::DIRECT_DEBIT:
				// @link https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/ddebit.php
				$icepay_method = new Icepay_Paymentmethod_Ddebit();

				break;
			case PaymentMethods::IDEAL:
				// @link https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/ideal.php
				$icepay_method = new Icepay_Paymentmethod_Ideal();

				break;
			case PaymentMethods::BANCONTACT:
			case PaymentMethods::MISTER_CASH:
				// @link https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/mistercash.php
				$icepay_method = new Icepay_Paymentmethod_Mistercash();

				break;
			case PaymentMethods::SOFORT:
				// @link https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/directebank.php
				$icepay_method = new Icepay_Paymentmethod_Directebank();

				// Set issuer.
				$issuer = 'DIGITAL';

				$lines = $payment->get_lines();

				if ( null !== $lines ) {
					foreach ( $lines as $line ) {
						$issuer = DirectebankIssuers::transform( $line->get_type() );

						break;
					}
				}

				$payment_object->setIssuer( $issuer );
		}

		if ( isset( $icepay_method ) ) {
			// @link https://github.com/icepay/icepay/blob/2.4.0/api/icepay_api_base.php#L342-L353
			$payment_object->setPaymentMethod( $icepay_method->getCode() );

			// Force language 'NL' for unsupported languages (i.e. 'EN' for iDEAL).
			if ( ! in_array( $language, $icepay_method->getSupportedLanguages(), true ) ) {
				$payment_object->setLanguage( 'NL' );
			}
		}

		// Protocol.
		$protocol = is_ssl() ? 'https' : 'http';

		// Basic mode.
		$basicmode = Icepay_Basicmode::getInstance();
		$basicmode
			->setMerchantID( $this->config->merchant_id )
			->setSecretCode( $this->config->secret_code )
			->setProtocol( $protocol )
			->setSuccessURL( $payment->get_return_url() )
			->setErrorURL( $payment->get_return_url() )
			->validatePayment( $payment_object );

		// Action URL.
		$payment->set_action_url( $basicmode->getURL() );
	}

	/**
	 * Update the status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public function update_status( Payment $payment ) {
		// Get the Icepay Result and set the required fields.
		$result = ( 'POST' === Server::get( 'REQUEST_METHOD' ) ? new Icepay_Postback() : new Icepay_Result() );

		$result
			->setMerchantID( $this->config->merchant_id )
			->setSecretCode( $this->config->secret_code );

		// Determine if the result can be validated.
		if ( ! $result->validate() ) {
			return;
		}

		// What was the status response.
		switch ( $result->getStatus() ) {
			case Icepay_StatusCode::SUCCESS:
				$payment->set_status( PaymentStatus::SUCCESS );

				break;
			case Icepay_StatusCode::OPEN:
				$payment->set_status( PaymentStatus::OPEN );

				break;
			case Icepay_StatusCode::ERROR:
				$status = PaymentStatus::FAILURE;

				// Check if payment is cancelled.
				if ( $result instanceof \Icepay_Postback ) {
					$data = $result->getPostback();
				} else {
					$data = $result->getResultData();
				}

				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				if ( \is_object( $data ) && isset( $data->statusCode ) && 'Cancelled' === $data->statusCode ) {
					$status = PaymentStatus::CANCELLED;
				}

				$payment->set_status( $status );

				break;
		}
	}
}
