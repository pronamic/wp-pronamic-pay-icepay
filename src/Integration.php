<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;

/**
 * Title: ICEPAY integration
 * Description:
 * Copyright: 2005-2022 Pronamic
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 2.0.6
 * @since 1.0.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct ICEPAY integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'icepay-ideal',
				'name'          => 'ICEPAY',
				'url'           => 'https://icepay.com/',
				'product_url'   => \__( 'https://icepay.com/nl/en/pricing-and-accounts/', 'pronamic_ideal' ),
				'manual_url'    => \__( 'https://www.pronamic.eu/support/how-to-connect-icepay-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://portal.icepay.com/',
				'provider'      => 'icepay',
				'supports'      => array(
					'webhook',
					'webhook_log',
				),
			)
		);

		parent::__construct( $args );

		// Actions
		$function = array( __NAMESPACE__ . '\Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	/**
	 * Get settings fields.
	 *
	 * @return array<int, array<string, callable|int|string|bool|array<int|string,int|string>>>
	 */
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
					/* translators: %s: default code */
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

		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_icepay_merchant_id', true );
		$config->secret_code = get_post_meta( $post_id, '_pronamic_gateway_icepay_secret_code', true );
		$config->order_id    = get_post_meta( $post_id, '_pronamic_gateway_icepay_order_id', true );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		return new Gateway( $this->get_config( $post_id ) );
	}
}
