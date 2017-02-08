<?php

/**
 * Title: ICEPAY gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.3.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * Constructs and intializes an ICEPAY gateway
	 *
	 * @param Pronamic_WP_Pay_Gateways_Icepay_Config $config
	 */
	public function __construct( Pronamic_WP_Pay_Gateways_Icepay_Config $config ) {
		parent::__construct( $config );

		// Default properties for this gateway
		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( 'icepay' );
	}

	//////////////////////////////////////////////////

	/**
	 * Filter iDEAL
	 */
	private function filter_ideal( $method ) {
		return is_array( $method ) && isset( $method['PaymentMethodCode'] ) && 'IDEAL' === $method['PaymentMethodCode'];
	}

	//////////////////////////////////////////////////

	/**
	 * Get issuers
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups  = array();
		$issuers = array();

		$methods = Icepay_Api_Webservice::getInstance()
			->paymentmethodService()
			->setMerchantID( $this->config->merchant_id )
			->setSecretCode( $this->config->secret_code )
			->retrieveAllPaymentmethods()
			->asArray();

		$ideal_methods = array_filter( $methods, array( $this, 'filter_ideal' ) );

		if ( ! empty( $ideal_methods ) ) {
			$issuers = Icepay_Api_Webservice::getInstance()->singleMethod()
				->loadFromArray( $methods )
				->selectPaymentMethodByCode( 'IDEAL' )
				->getIssuers();
		}

		if ( $issuers ) {
			$options = array();

			foreach ( $issuers as $issuer ) {
				$options[ $issuer['IssuerKeyword'] ] = $issuer['Description'];
			}

			$groups[] = array(
				'options' => $options,
			);
		}

		return $groups;
	}

	/////////////////////////////////////////////////

	/**
	 * Get issuer field.
	 *
	 * @since 1.0.0
	 * @version 1.2.4
	 * @return mixed
	 */
	public function get_issuer_field() {
		if ( Pronamic_WP_Pay_PaymentMethods::IDEAL === $this->get_payment_method() ) {
			return array(
				'id'       => 'pronamic_ideal_issuer_id',
				'name'     => 'pronamic_ideal_issuer_id',
				'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
				'required' => true,
				'type'     => 'select',
				'choices'  => $this->get_transient_issuers(),
			);
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Get payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_payment_methods()
	 */
	public function get_payment_methods() {
		return array( array(
				'options' => array(
					Pronamic_WP_Pay_PaymentMethods::IDEAL        => Pronamic_WP_Pay_PaymentMethods::IDEAL,
					Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD  => Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD,
					Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT => Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT,
					Pronamic_WP_Pay_PaymentMethods::BANCONTACT   => Pronamic_WP_Pay_PaymentMethods::MISTER_CASH,
				),
			),
		);
	}

	/////////////////////////////////////////////////

	/**
	 * Get supported payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			Pronamic_WP_Pay_PaymentMethods::IDEAL,
			Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD,
			Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT,
			Pronamic_WP_Pay_PaymentMethods::BANCONTACT,
		);
	}

	/////////////////////////////////////////////////

	/**
	 * Start an transaction
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_Payment $payment ) {
		try {
			$locale = $payment->get_locale();

			$language = strtoupper( substr( $locale, 0, 2 ) );
			$country  = strtoupper( substr( $locale, 3, 2 ) );

			/*
			 * Order ID
			 * Your unique order number.
			 * This can be auto incremental number from your payments table
			 *
			 * Data type  = String
			 * Max length = 10
			 * Required   = Yes
			 */

			// Payment object
			$payment_object = new Icepay_PaymentObject();
			$payment_object
				->setAmount( Pronamic_WP_Pay_Util::amount_to_cents( $payment->get_amount() ) )
				->setCountry( $country )
				->setLanguage( $language )
				->setReference( $payment->get_order_id() )
				->setDescription( $payment->get_description() )
				->setCurrency( $payment->get_currency() )
				->setIssuer( $payment->get_issuer() )
				->setOrderID( $payment->format_string( $this->config->order_id ) );

			/*
			 * Payment method
			 * @since 1.2.0
			 */
			$icepay_method = null;

			switch ( $payment->get_method() ) {
				case Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD :
					// @see https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/creditcard.php
					$icepay_method = new Icepay_Paymentmethod_Creditcard();

					break;
				case Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT :
					// @see https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/ddebit.php
					$icepay_method = new Icepay_Paymentmethod_Ddebit();

					break;
				case Pronamic_WP_Pay_PaymentMethods::IDEAL :
					// @see https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/ideal.php
					$icepay_method = new Icepay_Paymentmethod_Ideal();

					break;
				case Pronamic_WP_Pay_PaymentMethods::BANCONTACT :
				case Pronamic_WP_Pay_PaymentMethods::MISTER_CASH :
					// @see https://github.com/icepay/icepay/blob/2.4.0/api/paymentmethods/mistercash.php
					$icepay_method = new Icepay_Paymentmethod_Mistercash();

					break;
			}

			if ( isset( $icepay_method ) ) {
				// @see https://github.com/icepay/icepay/blob/2.4.0/api/icepay_api_base.php#L342-L353
				$payment_object->setPaymentMethod( $icepay_method->getCode() );
			}

			// Protocol
			$protocol = is_ssl() ? 'https' : 'http';

			// Basic mode
			$basicmode = Icepay_Basicmode::getInstance();
			$basicmode
				->setMerchantID( $this->config->merchant_id )
				->setSecretCode( $this->config->secret_code )
				->setProtocol( $protocol )
				->validatePayment( $payment_object );

			// Payment
			$payment->set_action_url( $basicmode->getURL() );
		} catch ( Exception $exception ) {
			$this->error = new WP_Error( 'icepay_error', $exception->getMessage(), $exception );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Update the status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 * @throws Exception
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		// Get the Icepay Result and set the required fields
		$result = new Icepay_Result();
		$result
			->setMerchantID( $this->config->merchant_id )
			->setSecretCode( $this->config->secret_code );

		try {
			// Determine if the result can be validated
			if ( $result->validate() ) {
				// What was the status response
				switch ( $result->getStatus() ) {
					case Icepay_StatusCode::SUCCESS :
						$payment->set_status( Pronamic_WP_Pay_Statuses::SUCCESS );

						break;
					case Icepay_StatusCode::OPEN :
						$payment->set_status( Pronamic_WP_Pay_Statuses::OPEN );

						break;
					case Icepay_StatusCode::ERROR :
						$payment->set_status( Pronamic_WP_Pay_Statuses::FAILURE );

						break;
				}
			}
		} catch ( Exception $exception ) {
			$this->error = new WP_Error( 'icepay_error', $exception->getMessage(), $exception );
		}
	}
}
