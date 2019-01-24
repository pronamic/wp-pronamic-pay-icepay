<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: ICEPAY config
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.3.0
 * @since 1.0.0
 */
class Config extends GatewayConfig {
	public $merchant_id;

	public $secret_code;

	public $order_id;
}
