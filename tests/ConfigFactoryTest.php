<?php

namespace Pronamic\WordPress\Pay\Gateways\Icepay;

/**
 * Title: ICEPAY - Config factory test
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.0
 */
class Pronamic_WP_Pay_Gateways_Icepay_ConfigFactoryTest extends \PHPUnit_Framework_TestCase {
	public function test_config() {
		$factory = new ConfigFactory();

		$config = $factory->get_config( 0 );

		$this->assertInstanceOf( __NAMESPACE__ . '\Config', $config );
	}
}
