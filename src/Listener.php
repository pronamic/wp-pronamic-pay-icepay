<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Core\Util;
use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: ICEPAY listener
 * Description:
 * Copyright: 2005-2024 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.6
 */
class Listener {
	/**
	 * Listen.
	 *
	 * @return void
	 */
	public static function listen() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$parameters = [
			'Status',
			'StatusCode',
			'Merchant',
			'OrderID',
			'PaymentID',
			'Reference',
			'TransactionID',
			'Checksum',
		];

		foreach ( $parameters as $parameter ) {
			if ( ! \array_key_exists( $parameter, $_POST ) ) {
				return;
			}
		}

		$payment_id = array_key_exists( 'OrderID', $_POST ) ? \sanitize_text_field( \wp_unslash( $_POST['OrderID'] ) ) : null;

		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$payment = get_pronamic_payment( $payment_id );

		if ( null === $payment ) {
			return;
		}

		// Add note.
		$note = sprintf(
			/* translators: %s: payment provider name */
			__( 'Webhook requested by %s.', 'pronamic_ideal' ),
			__( 'ICEPAY', 'pronamic_ideal' )
		);

		$payment->add_note( $note );

		// Log webhook request.
		do_action( 'pronamic_pay_webhook_log_payment', $payment );

		// Update payment.
		Plugin::update_payment( $payment );
	}
}
