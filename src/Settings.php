<?php

/**
 * Title: ICEPAY gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.7
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_Settings extends Pronamic_WP_Pay_GatewaySettings {
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	public function sections( array $sections ) {
		// iDEAL
		$sections['icepay'] = array(
			'title'   => __( 'ICEPAY', 'pronamic_ideal' ),
			'methods' => array( 'icepay' ),
			'description' => __( 'Account details are provided by the payment provider after registration. These settings need to match with the payment provider dashboard.', 'pronamic_ideal' ),
		);

		// Transaction feedback
		$sections['icepay_feedback'] = array(
			'title'   => __( 'Transaction feedback', 'pronamic_ideal' ),
			'methods' => array( 'icepay' ),
			'description' => __( 'Set the below URLs in the payment provider dashboard to receive automatic transaction status updates.', 'pronamic_ideal' ),
		);

		// Return
		return $sections;
	}

	public function fields( array $fields ) {
		// Merchant ID
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'icepay',
			'meta_key'    => '_pronamic_gateway_icepay_merchant_id',
			'title'       => _x( 'Merchant ID', 'icepay', 'pronamic_ideal' ),
			'type'        => 'text',
			'tooltip'     => __( 'Merchant ID as mentioned in the ICEPAY dashboard at the "My websites" page.', 'pronamic_ideal' ),
		);

		// Secret Code
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'icepay',
			'meta_key'    => '_pronamic_gateway_icepay_secret_code',
			'title'       => _x( 'Secret Code', 'icepay', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'tooltip'     => __( 'Secret Code as mentioned in the ICEPAY dashboard at the "My websites" page.', 'pronamic_ideal' ),
		);

		// Transaction feedback
		$fields[] = array(
			'section'       => 'icepay',
			'title'         => __( 'Transaction feedback', 'pronamic_ideal' ),
			'type'          => 'description',
			'html'          => sprintf(
				'<span class="dashicons dashicons-warning"></span> %s',
				__( 'Receiving payment status updates needs additional configuration, if not yet completed.', 'pronamic_ideal' )
			),
		);

		// Thank you page URL
		$fields[] = array(
			'section'     => 'icepay_feedback',
			'title'       => __( 'Thank you page URL', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'value'       => home_url( '/' ),
			'readonly'    => true,
		);

		// Error page URL
		$fields[] = array(
			'section'     => 'icepay_feedback',
			'title'       => __( 'Error page URL', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'value'       => home_url( '/' ),
			'readonly'    => true,
		);

		// Postback URL
		$fields[] = array(
			'section'     => 'icepay_feedback',
			'title'       => __( 'Postback URL', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'value'       => home_url( '/' ),
			'readonly'    => true,
		);

		// Return
		return $fields;
	}
}
