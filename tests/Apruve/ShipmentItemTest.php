<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/ShipmentItem.php';

use Apruve\ShipmentItem;

class ShipmentItemTest extends \PHPUnit\Framework\TestCase {

	public function testPropertiesAreDefined() {
		$item_vars = get_class_vars( get_class( $this->shipmentItem ) );

		$this->assertEquals( array_keys( $item_vars ), array(
			'title',
			'amount_cents',
			'quantity',
			'price_ea_cents',
			'merchant_notes',
			'description',
			'variant_info',
			'sku',
			'vendor',
			'view_product_url',
			'shipping_cents',
			'tax_cents',
			'price_total_cents',
			'currency'
		) );
		$this->assertEquals( 14, count( $item_vars ) );
	}

	public function testToJsonString() {
		$this->assertJsonStringEqualsJsonString(
			'{
          "title": "A title",
          "amount_cents": 3400,
          "quantity": 1,
          "price_ea_cents": 3400,
          "merchant_notes": "some notes.",
          "description": "a description.",
          "variant_info": "some variation.",
          "sku": "a sku",
          "vendor": "ACME",
          "view_product_url": "A Url.",
          "shipping_cents": 1000,
          "tax_cents": 2601,
          "price_total_cents": 3400,
          "currency": "USD"
       }', $this->shipmentItem->toJson()
		);
	}

	protected function setUp() {
		$this->shipmentItem = new ShipmentItem( [
			'title'             => 'A title',
			'amount_cents'      => 3400,
			'quantity'          => 1,
			'price_ea_cents'    => 3400,
			'merchant_notes'    => 'some notes.',
			'description'       => 'a description.',
			'variant_info'      => 'some variation.',
			'sku'               => 'a sku',
			'vendor'            => 'ACME',
			'view_product_url'  => 'A Url.',
			'shipping_cents'    => 1000,
			'tax_cents'         => 2601,
			'price_total_cents' => 3400,
			'currency'          => 'USD'
		], $this->getMockBuilder( 'Apruve\Client' )
		        ->setConstructorArgs( [ 'a key', Apruve\Environment::DEV() ] )
		        ->getMock()
		);
	}

	protected function tearDown() {

	}
}

