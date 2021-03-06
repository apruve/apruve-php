<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/ApruveObject.php';

use Apruve\ApruveObject;

class TestClass extends ApruveObject {
	protected static $hash_order = [
		'first',
		'second',
		'third',
	];
	protected static $json_fields = [
		'second',
		'first'
	];
	var $first;
	var $third;
	var $second;

	public function getClient() {
		return $this->client;
	}
}

class ApruveObjectTest extends \PHPUnit\Framework\TestCase {

	public function testPropertiesAreDefined() {
		$vars = get_class_vars( get_class( $this->object ) );

		$this->assertEquals( array_keys( $vars ), array(
			'first',
			'third',
			'second',
		) );
		$this->assertEquals( 3, count( $vars ) );
	}

	public function testToHashString() {
		$this->assertEquals(
			'1second3', $this->object->toHashString() );
	}

	public function testToJson() {
		$this->assertJsonStringEqualsJsonString(
			'{"first": 1, "second": "second" }',
			$this->object->toJson()
		);
	}

	public function testClientInjection() {
		$this->client = Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->assertEquals( $this->client->getApiKey(), $this->object->getClient()->getApiKey() );
	}

	protected function setUp() {
		$this->client = Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->object = new TestClass( [
			'third'  => 3,
			'first'  => 1,
			'second' => 'second',
		] );
	}
}
