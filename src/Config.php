<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: ICEPAY config
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
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
