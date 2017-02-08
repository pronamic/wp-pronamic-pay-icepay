<?php

/**
 * Title: ICEPAY listener
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.1.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_Listener implements Pronamic_Pay_Gateways_ListenerInterface {
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

			Pronamic_WP_Pay_Plugin::update_payment( $payment );
		}
	}
}
