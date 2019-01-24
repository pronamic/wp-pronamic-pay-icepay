<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Core\GatewayConfigFactory;

/**
 * Title: ICEPAY config factory
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.0
 * @since 1.0.0
 */
class ConfigFactory extends GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_icepay_merchant_id', true );
		$config->secret_code = get_post_meta( $post_id, '_pronamic_gateway_icepay_secret_code', true );
		$config->order_id    = get_post_meta( $post_id, '_pronamic_gateway_icepay_order_id', true );

		return $config;
	}
}
