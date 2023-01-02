<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use JsonSerializable;
use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: ICEPAY config
 * Description:
 * Copyright: 2005-2023 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.3.0
 * @since 1.0.0
 */
class Config extends GatewayConfig implements JsonSerializable {
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

	/**
	 * JSON serialize.
	 *
	 * @return object
	 */
	public function jsonSerialize(): object {
		return (object) [
			'merchant_id' => $this->merchant_id,
			'secret_code' => $this->secret_code,
		];
	}
}
