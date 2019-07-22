<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

/**
 * Title: ICEPAY - Integration test
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.0
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Integration.
	 *
	 * @var Integration
	 */
	public $integration;

	/**
	 * Setup.
	 */
	public function setUp() {
		$this->integration = new Integration();
	}

	/**
	 * Test config.
	 */
	public function test_config() {
		$config = $this->integration->get_config( 99 );

		$this->assertInstanceOf( __NAMESPACE__ . '\Config', $config );
	}

	/**
	 * Test gateway.
	 */
	public function test_gateway() {
		$gateway = $this->integration->get_gateway( 99 );

		$this->assertInstanceOf( __NAMESPACE__ . '\Gateway', $gateway );
	}

	/**
	 * Test settings.
	 */
	public function test_settings() {
		$this->assertInternalType( 'array', $this->integration->get_settings_fields() );
	}
}
