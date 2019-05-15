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
	public function test_config() {
		$integration = new Integration();

		$expected = __NAMESPACE__ . '\ConfigFactory';

		$class = $integration->get_config_factory_class();

		$this->assertEquals( $expected, $class );
		$this->assertTrue( class_exists( $class ) );
	}
}
