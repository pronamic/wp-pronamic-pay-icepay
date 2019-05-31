<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;

/**
 * Title: ICEPAY integration
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 2.0.0
 * @since 1.0.0
 */
class Integration extends AbstractIntegration {
	public function __construct() {
		$this->id            = 'icepay-ideal';
		$this->name          = 'ICEPAY';
		$this->url           = 'https://icepay.com/';
		$this->product_url   = __( 'https://icepay.com/nl/en/pricing-and-accounts/', 'pronamic_ideal' );
		$this->dashboard_url = 'https://portal.icepay.com/';
		$this->provider      = 'icepay';

		// Actions
		$function = array( __NAMESPACE__ . '\Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	public function get_config_factory_class() {
		return __NAMESPACE__ . '\ConfigFactory';
	}

	public function get_settings_fields() {
		$fields = array();

		// Merchant ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_icepay_merchant_id',
			'title'    => _x( 'Merchant ID', 'icepay', 'pronamic_ideal' ),
			'type'     => 'text',
			'tooltip'  => __( 'Merchant ID as mentioned in the ICEPAY dashboard at the "My websites" page.', 'pronamic_ideal' ),
		);

		// Secret Code
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_icepay_secret_code',
			'title'    => _x( 'Secret Code', 'icepay', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Secret Code as mentioned in the ICEPAY dashboard at the "My websites" page.', 'pronamic_ideal' ),
		);

		// Purchase ID
		$fields[] = array(
			'section'     => 'advanced',
			'filter'      => array(
				'filter' => FILTER_SANITIZE_STRING,
				'flags'  => FILTER_FLAG_NO_ENCODE_QUOTES,
			),
			'meta_key'    => '_pronamic_gateway_icepay_order_id',
			'title'       => __( 'Order ID', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'tooltip'     => sprintf(
				/* translators: %s: <code>OrderID</code> */
				__( 'The Icepay %s parameter.', 'pronamic_ideal' ),
				sprintf( '<code>%s</code>', 'OrderID' )
			),
			'description' => sprintf(
				'%s %s<br />%s',
				__( 'Available tags:', 'pronamic_ideal' ),
				sprintf(
					'<code>%s</code> <code>%s</code>',
					'{order_id}',
					'{payment_id}'
				),
				sprintf(
					/* translators: %s: <code>{payment_id}</code> */
					__( 'Default: <code>%s</code>', 'pronamic_ideal' ),
					'{payment_id}'
				)
			),
		);

		// Thank you page URL
		$fields[] = array(
			'section'  => 'feedback',
			'title'    => __( 'Thank you page URL', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'value'    => home_url( '/' ),
			'readonly' => true,
		);

		// Error page URL
		$fields[] = array(
			'section'  => 'feedback',
			'title'    => __( 'Error page URL', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'value'    => home_url( '/' ),
			'readonly' => true,
		);

		// Postback URL
		$fields[] = array(
			'section'  => 'feedback',
			'title'    => __( 'Postback URL', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'value'    => home_url( '/' ),
			'readonly' => true,
		);

		// Webhook status.
		$fields[] = array(
			'section'  => 'feedback',
			'methods'  => array( 'icepay' ),
			'title'    => __( 'Status', 'pronamic_ideal' ),
			'type'     => 'description',
			'callback' => array( $this, 'feedback_status' ),
		);

		return $fields;
	}
}
