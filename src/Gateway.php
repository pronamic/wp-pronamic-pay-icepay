<?php

/**
 * Title: ICEPAY gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
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
	 * Start an transaction
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment, $payment_method = null ) {
		try {
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
				->setAmount( Pronamic_WP_Util::amount_to_cents( $data->get_amount() ) )
				->setCountry( 'NL' )
				->setLanguage( 'NL' )
				->setReference( $data->get_order_id() )
				->setDescription( $data->get_description() )
				->setCurrency( $data->get_currency() )
				->setIssuer( $data->get_issuer_id() )
				->setOrderID( $payment->get_id() );

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
