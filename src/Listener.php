<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Core\Util;
use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: ICEPAY listener
 * Description:
 * Copyright: 2005-2022 Pronamic
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
		$variable_names = array(
			'Status',
			'StatusCode',
			'Merchant',
			'OrderID',
			'PaymentID',
			'Reference',
			'TransactionID',
			'Checksum',
		);

		if ( ! Util::input_has_vars( INPUT_POST, $variable_names ) ) {
			return;
		}

		$reference = filter_input( INPUT_POST, 'OrderID', FILTER_SANITIZE_STRING );

		$payment = get_pronamic_payment( $reference );

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
