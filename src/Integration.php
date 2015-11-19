<?php

class Pronamic_WP_Pay_Gateways_Icepay_Integration extends Pronamic_WP_Pay_Gateways_AbstractIntegration {
	public function __construct() {
		$this->id            = 'icepay-ideal';
		$this->name          = 'ICEPAY';
		$this->url           = 'https://icepay.com/';
		$this->dashboard_url = 'https://portal.icepay.com/';

		// Actions
		$function = array( 'Pronamic_WP_Pay_Gateways_Icepay_Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_ConfigFactory';
	}

	public function get_config_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_Config';
	}

	public function get_settings_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_Settings';
	}

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_Gateway';
	}
}
