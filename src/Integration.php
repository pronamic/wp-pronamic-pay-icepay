<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;

/**
 * Title: ICEPAY integration
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 2.0.0
 * @since 1.0.0
 */
class Integration extends AbstractIntegration {
	public function __construct() {
		$this->id            = 'icepay-ideal';
		$this->name          = 'ICEPAY';
		$this->url           = 'https://icepay.com/';
		$this->product_url   = __( 'https://icepay.com/nl/en/pricing-and-accounts/', 'pronamic_ideal' );
		$this->dashboard_url = 'https://portal.icepay.com/';
		$this->provider      = 'icepay';

		// Actions
		$function = array( __NAMESPACE__ . '\Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	public function get_config_factory_class() {
		return __NAMESPACE__ . '\ConfigFactory';
	}

	public function get_settings_class() {
		return __NAMESPACE__ . '\Settings';
	}

	/**
	 * Get required settings for this integration.
	 *
	 * @link https://github.com/wp-premium/gravityforms/blob/1.9.16/includes/fields/class-gf-field-multiselect.php#L21-L42
	 * @since 1.0.2
	 * @return array
	 */
	public function get_settings() {
		$settings = parent::get_settings();

		$settings[] = 'icepay';

		return $settings;
	}
}
