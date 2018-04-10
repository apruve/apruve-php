<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/Shipment.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/ShipmentItem.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Client.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';


use Apruve\Shipment;

class ShipmentTest extends \PHPUnit\Framework\TestCase {

	public function testPropertiesAreDefined() {
		$item_vars = get_class_vars( get_class( $this->shipment ) );

		$this->assertEquals( array_keys( $item_vars ), array(
			'id',
			'amount_cents',
			'invoice_id',
			'shipper',
			'tracking_number',
			'shipped_at',
			'delivered_at',
			'merchant_notes',
			'uuid',
			'created_at',
			'currency',
			'tax_cents',
			'shipping_cents',
			'status',
			'merchant_shipment_id',
			'shipment_items'
		) );
		$this->assertEquals( 16, count( $item_vars ) );
	}

	public function testToJsonString() {
		$this->assertJsonStringEqualsJsonString(
			'{
          "id": "uniqueid",
          "amount_cents": 34000,
          "invoice_id": "730cd103b8e8377359851498e5c34f7b",
          "shipper": "UPS",
          "tracking_number": "alphabetagamma",
          "shipped_at": "2017-07-01T10:12:27-05:00",
          "delivered_at": "2017-07-22T10:12:27-05:00",
          "merchant_notes": "i am notes",
          "uuid": "91ac96c0ffc9577ecb634ad726b1874e",
          "created_at": "2017-06-01T10:12:27-05:00",
          "currency": "USD",
          "tax_cents": 2601,
          "shipping_cents": 1000,
          "status": "fulfilled",
          "merchant_shipment_id": "merchantspecified",
          "shipment_items": [
          {
            "title": "a title",
            "amount_cents": 4500,
            "price_ea_cents": null,
            "quantity": null,
            "merchant_notes": null,
            "description": null,
            "variant_info": null,
            "sku": null,
            "vendor": null,
            "view_product_url": null,
            "shipping_cents": null,
            "tax_cents": null,
            "price_total_cents": null,
            "currency": null
          }
        ]
       }', $this->shipment->toJson()
		);
	}

	public function testAddShipmentItem() {
		$item_count = count( $this->shipment->shipment_items );
		$this->shipment->addShipmentItem(
			new \Apruve\ShipmentItem( [
				'title'        => 'A new title',
				'amount_cents' => 4521
			] )
		);
		$this->assertEquals( $item_count + 1, count( $this->shipment->shipment_items ) );
	}

	public function testAddShipmentItemOnlyAllowsShipmentItems() {
		$this->assertEquals( false, $this->shipment->addShipmentItem( new Shipment ) );
	}

	public function testGet() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'get' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'get' )
		       ->with( $this->equalTo( '/invoices/asdf1234/shipments' ) )
		       ->will( $this->returnValue( [
			       200,
			       [
				       'invoice_id'     => 'asdf1234',
				       'amount_cents'   => 6000,
				       'tax_cents'      => 500,
				       'shipping_cents' => 1000,
				       'currency'       => 'USD'
			       ],
			       ''
		       ] )
		       );

		$shipment = Shipment::get( 'asdf1234', $client );

		$this->assertEquals( 'asdf1234', $shipment->invoice_id );
		$this->assertEquals( 'Apruve\Shipment', get_class( $shipment ) );
	}

	protected function setUp() {
		Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->shipment = new Shipment( [
			'id'                   => 'uniqueid',
			'amount_cents'         => 34000,
			'invoice_id'           => '730cd103b8e8377359851498e5c34f7b',
			'shipper'              => 'UPS',
			'tracking_number'      => 'alphabetagamma',
			'shipped_at'           => '2017-07-01T10:12:27-05:00',
			'delivered_at'         => '2017-07-22T10:12:27-05:00',
			'merchant_notes'       => 'i am notes',
			'uuid'                 => '91ac96c0ffc9577ecb634ad726b1874e',
			'created_at'           => '2017-06-01T10:12:27-05:00',
			'currency'             => 'USD',
			'tax_cents'            => 2601,
			'shipping_cents'       => 1000,
			'status'               => 'fulfilled',
			'merchant_shipment_id' => 'merchantspecified',
			'shipment_items'       => [
				[
					'title'            => 'a title',
					'amount_cents'     => 4500,
					'price_ea_cents'   => null,
					'quantity'         => null,
					'merchant_notes'   => null,
					'description'      => null,
					'variant_info'     => null,
					'sku'              => null,
					'vendor'           => null,
					'view_product_url' => null
				]
			],
		], $this->getMockBuilder( 'Apruve\Client' )
		        ->setConstructorArgs( [ 'a key', Apruve\Environment::DEV() ] )
		        ->getMock()
		);
	}

	protected function tearDown() {

	}
}

