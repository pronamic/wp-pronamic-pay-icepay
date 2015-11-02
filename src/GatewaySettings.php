<?php

/**
 * Title: ICEPAY gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.3.0
 * @since 1.3.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_GatewaySettings extends Pronamic_WP_Pay_Admin_GatewaySettings {
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	public function sections( array $sections ) {
		// iDEAL
		$sections['icepay'] = array(
			'title'   => __( 'ICEPAY', 'pronamic_ideal' ),
			'methods' => array( 'icepay' ),
		);

		// Return
		return $sections;
	}

	public function fields( array $fields ) {
		// Partner ID
		$fields[] = array(
			'section'     => 'icepay',
			'meta_key'    => '_pronamic_gateway_icepay_merchant_id',
			'title'       => _x( 'Merchant ID', 'icepay', 'pronamic_ideal' ),
			'type'        => 'text',
			'description' => sprintf(
				__( 'You can find your Merchant ID on your <a href="%s" target="_blank">ICEPAY account page</a> under <a href="%s" target="_blank">My websites</a>.', 'pronamic_ideal' ),
				__( 'https://portal.icepay.com/EN/Login', 'pronamic_ideal' ),
				__( 'https://portal.icepay.com/Merchant/EN/Websites', 'pronamic_ideal' )
			),
		);

		$fields[] = array(
			'section'     => 'icepay',
			'meta_key'    => '_pronamic_gateway_icepay_secret_code',
			'title'       => _x( 'Secret Code', 'icepay', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'description' => sprintf(
				__( 'You can find your Secret Code on your <a href="%s" target="_blank">ICEPAY account page</a> under <a href="%s" target="_blank">My websites</a>.', 'pronamic_ideal' ),
				__( 'https://portal.icepay.com/EN/Login', 'pronamic_ideal' ),
				__( 'https://portal.icepay.com/Merchant/EN/Websites', 'pronamic_ideal' )
			),
		);

		$fields[] = array(
			'section'     => 'icepay',
			'title'       => __( 'Thank you page URL', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'value'       => home_url( '/' ),
			'readonly'    => true,
		);

		$fields[] = array(
			'section'     => 'icepay',
			'title'       => __( 'Error page URL', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'value'       => home_url( '/' ),
			'readonly'    => true,
		);

		$fields[] = array(
			'section'     => 'icepay',
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
