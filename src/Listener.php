<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: ICEPAY listener
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.0
 */
class Listener {
	public static function listen() {
		if (
			filter_has_var( INPUT_GET, 'Status' )
			&&
			filter_has_var( INPUT_GET, 'StatusCode' )
			&&
			filter_has_var( INPUT_GET, 'Merchant' )
			&&
			filter_has_var( INPUT_GET, 'OrderID' )
			&&
			filter_has_var( INPUT_GET, 'PaymentID' )
			&&
			filter_has_var( INPUT_GET, 'Reference' )
			&&
			filter_has_var( INPUT_GET, 'TransactionID' )
			&&
			filter_has_var( INPUT_GET, 'Checksum' )
		) {
			$reference = filter_input( INPUT_GET, 'OrderID', FILTER_SANITIZE_STRING );

			$payment = get_pronamic_payment( $reference );

			// Add note.
			$note = sprintf(
				/* translators: %s: ICEPAY */
				__( 'Webhook requested by %s.', 'pronamic_ideal' ),
				__( 'ICEPAY', 'pronamic_ideal' )
			);

			$payment->add_note( $note );

			// Update payment.
			Plugin::update_payment( $payment );
		}
	}
}
