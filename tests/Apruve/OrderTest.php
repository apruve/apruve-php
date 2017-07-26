<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/Order.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/OrderItem.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Client.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';

use Apruve\Order;
use Apruve\OrderItem;

class OrderTest extends PHPUnit_Framework_TestCase {

	public function testPropertiesAreDefined() {
		$vars = get_class_vars( get_class( $this->po ) );

		$this->assertEquals( array_keys( $vars ), [
			'id',
			'merchant_id',
			'username',
			'status',
			'merchant_order_id',
			'amount_cents',
			'tax_cents',
			'shipping_cents',
			'currency',
			'expire_at',
			'invoice_on_create',
			'order_items',
			'api_url',
			'view_url',
			'created_at',
			'updated_at',
		] );
		$this->assertEquals( 16, count( $vars ) );

	}

	public function testToJson() {
		$this->assertJsonStringEqualsJsonString(
			'{
        "id": "id",
        "merchant_id": "asdf1234",
        "merchant_order_id": "order1234",
        "amount_cents": 6000,
        "tax_cents": 500,
        "shipping_cents": 1000,
        "currency": "USD",
        "expire_at": "2014-07-15T10:12:27-05:00",
        "order_items": [
          {
            "title": "a title",
            "amount_cents": 4500,
            "plan_code": null,
            "price_ea_cents": null,
            "quantity": null,
            "merchant_notes": null,
            "description": null,
            "variant_info": null,
            "sku": null,
            "vendor": null,
            "view_product_url": null
          }
        ],
        "invoice_on_create": "false"
       }',
			$this->po->toJson()
		);
	}

	public function testToHashString() {
		$this->assertEquals(
			'asdf1234order12346000USD50010002014-07-15T10:12:27-05:00false',
			$this->po->toHashString()
		);
	}

	public function testToSecureString() {
		$this->assertEquals(
			'asdf1234order12346000USD50010002014-07-15T10:12:27-05:00falsea title4500',
			$this->po->toSecureString()
		);
	}

	public function testToSecureHash() {
		Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->assertEquals(
			hash( 'sha256', 'a keyasdf1234order12346000USD50010002014-07-15T10:12:27-05:00falsea title4500' ),
			$this->po->toSecureHash()
		);
	}

	public function testAddOrderItem() {
		$item_count = count( $this->po->order_items );
		$this->po->addOrderItem(
			new OrderItem( [
				'title'        => 'A new title',
				'amount_cents' => 4521,
			] )
		);
		$this->assertEquals( $item_count + 1, count( $this->po->order_items ) );

	}

	public function testAddOrderItemOnlyAllowsOrderItems() {
		$this->assertEquals( false, $this->po->addOrderItem( new Order ) );
	}

	public function testGet() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'get' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'get' )
		       ->with( $this->equalTo( '/orders/asdf1234' ) )
		       ->will( $this->returnValue( [
			       200,
			       [
				       'id'                => 'asdf1234',
				       'merchant_id'       => 'asdf1234',
				       'merchant_order_id' => 'order1234',
				       'amount_cents'      => 6000,
				       'tax_cents'         => 500,
				       'shipping_cents'    => 1000,
				       'currency'          => 'USD',
			       ],
			       ''
		       ] )
		       );

		$po = Order::get( 'asdf1234', $client );

		$this->assertEquals( 'asdf1234', $po->id );
		$this->assertEquals( 'Apruve\Order', get_class( $po ) );
	}

	public function testUpdate() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'put' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'put' )
		       ->with( $this->equalTo( '/orders/asdf1234' ) )
		       ->will( $this->returnValue( [
			       200,
			       [
				       'id'                => 'asdf1234',
				       'merchant_id'       => 'asdf1234',
				       'merchant_order_id' => 'order1234',
				       'amount_cents'      => 6000,
				       'tax_cents'         => 500,
				       'shipping_cents'    => 1000,
				       'currency'          => 'USD',
			       ],
			       ''
		       ] )
		       );

		$po = new Order( [
			'id'                => 'asdf1234',
			'merchant_id'       => 'asdf1234',
			'merchant_order_id' => 'order1234',
			'amount_cents'      => 6000,
			'currency'          => 'USD'
		], $client );
		$po->setShippingCents( 500 );
		$po->setTaxCents( 439 );
		$po->setAmountCents( 6199 );

		$po = $po->update();

		$this->assertEquals( true, $po );
	}

	protected function setUp() {
		Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->po = new Order( [
			'id'                => 'id',
			'merchant_id'       => 'asdf1234',
			'merchant_order_id' => 'order1234',
			'amount_cents'      => 6000,
			'tax_cents'         => 500,
			'shipping_cents'    => 1000,
			'currency'          => 'USD',
			'expire_at'         => '2014-07-15T10:12:27-05:00',
			'order_items'       => [
				[
					'title'        => 'a title',
					'amount_cents' => 4500,
					'order_id'     => 'id',
				]
			],
		] );
	}


}

