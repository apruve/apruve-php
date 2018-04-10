<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';

class ApruveEnvironmentTest extends \PHPUnit\Framework\TestCase {

	public function testURLs() {
		$this->assertEquals( 'https://app.apruve.com', Apruve\Environment::PROD );
		$this->assertEquals( 'https://test.apruve.com', Apruve\Environment::TEST );
		$this->assertEquals( 'http://localhost:3000', Apruve\Environment::DEV );

	}

	public function testGetJsUrl() {
		$env = Apruve\Environment::TEST();
		$this->assertEquals(
			'https://test.apruve.com/js/apruve.js',
			$env->getJsUrl()
		);
	}

	public function testGetApiUrl() {
		$env = Apruve\Environment::TEST();
		$this->assertEquals( 'https://test.apruve.com/api/v4', $env->getApiUrl() );
	}

	public function testGetBaseUrl() {
		$env = Apruve\Environment::TEST();
		$this->assertEquals( 'https://test.apruve.com', $env->getBaseUrl() );
	}

	public function testPRODGetBaseUrl() {
		$env = Apruve\Environment::PROD();
		$this->assertEquals( 'https://app.apruve.com', $env->getBaseUrl() );
	}

	protected function setUp() {
	}


}
