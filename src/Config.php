<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: ICEPAY config
 * Description:
 * Copyright: 2005-2022 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.3.0
 * @since 1.0.0
 */
class Config extends GatewayConfig {
	/**
	 * Merchant ID.
	 *
	 * @var string
	 */
	public $merchant_id;

	/**
	 * Secret code.
	 *
	 * @var string
	 */
	public $secret_code;

	/**
	 * Order ID.
	 *
	 * @var string
	 */
	public $order_id;
}
