<?php

class Pronamic_WP_Pay_Gateways_Icepay_Integration {
	public function __construct() {
		$this->id            = 'icepay-ideal';
		$this->name          = 'ICEPAY';
		$this->url           = 'https://icepay.com/';
		$this->dashboard_url = 'https://portal.icepay.com/',
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_ConfigFactory';
	}

	public function get_config_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_Config';
	}

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_Gateway';
	}
}
