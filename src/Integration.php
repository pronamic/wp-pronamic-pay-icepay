<?php

/**
 * Title: ICEPAY integration
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.2.7
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_Integration extends Pronamic_WP_Pay_Gateways_AbstractIntegration {
	public function __construct() {
		$this->id            = 'icepay-ideal';
		$this->name          = 'ICEPAY';
		$this->url           = 'https://icepay.com/';
		$this->product_url   = __( 'https://icepay.com/nl/en/pricing-and-accounts/', 'pronamic_ideal' );
		$this->dashboard_url = 'https://portal.icepay.com/';
		$this->provider      = 'icepay';

		// Actions
		$function = array( 'Pronamic_WP_Pay_Gateways_Icepay_Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_ConfigFactory';
	}

	public function get_settings_class() {
		return 'Pronamic_WP_Pay_Gateways_Icepay_Settings';
	}

	/**
	 * Get required settings for this integration.
	 *
	 * @see https://github.com/wp-premium/gravityforms/blob/1.9.16/includes/fields/class-gf-field-multiselect.php#L21-L42
	 * @since 1.0.2
	 * @return array
	 */
	public function get_settings() {
		$settings = parent::get_settings();

		$settings[] = 'icepay';

		return $settings;
	}
}
