<?php
use Pronamic\WordPress\Pay\Gateways\Icepay\Integration;

/**
 * Title: ICEPAY - Integration test
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_IntegrationTest extends PHPUnit_Framework_TestCase {
	public function test_config() {
		$integration = new Integration();

		$expected = 'Pronamic\WordPress\Pay\Gateways\Icepay\ConfigFactory';

		$class = $integration->get_config_factory_class();

		$this->assertEquals( $expected, $class );
		$this->assertTrue( class_exists( $class ) );
	}
}
