<?php
/**
 * Directebank Issuers.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Icepay
 */

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Payments\PaymentLineType;

/**
 * Product categories.
 *
 * @author  Reüel van der Steege
 * @version 2.0.5
 * @since   2.0.5
 */
class DirectebankIssuers {
	/**
	 * Issuer 'RETAIL'.
	 *
	 * @var string
	 */
	const RETAIL = 'RETAIL';

	/**
	 * Issuer 'DIGITAL'.
	 *
	 * @var string
	 */
	const DIGITAL = 'DIGITAL';

	/**
	 * Issuer 'ADULT'.
	 *
	 * @var string
	 */
	const ADULT = 'ADULT';

	/**
	 * Transform Pronamic payment line type to Icepay Directebank issuer.
	 *
	 * @param string $type Pronamic payment line type.
	 *
	 * @return string
	 */
	public static function transform( $type ) {
		switch ( $type ) {
			case PaymentLineType::PHYSICAL:
				return self::RETAIL;

			case PaymentLineType::DIGITAL:
			case PaymentLineType::DISCOUNT:
			case PaymentLineType::SHIPPING:
			default:
				return self::DIGITAL;
		}
	}
}
