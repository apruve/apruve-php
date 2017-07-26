<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/Invoice.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/InvoiceItem.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Client.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';

use Apruve\Invoice;

class InvoiceTest extends PHPUnit_Framework_TestCase {

	public function testPropertiesAreDefined() {
		$vars = get_class_vars( get_class( $this->invoice ) );

		$this->assertEquals( array_keys( $vars ), [
			'id',
			'order_id',
			'status',
			'amount_cents',
			'price_total_cents',
			'currency',
			'merchant_notes',
			'api_url',
			'view_url',
			'created_at',
			'updated_at',
			'invoice_items',
		] );
		$this->assertEquals( 12, count( $vars ) );

	}

	public function testToJson() {
		$this->assertJsonStringEqualsJsonString(
			'{
        "amount_cents": 6000,
        "currency": "USD",
        "merchant_notes": null,
        "invoice_items": [
          {
            "title": "test",
            "amount_cents": 100,
            "quantity" : 1,
            "price_ea_cents": 100,
            "merchant_notes": null,
            "description": null,
            "variant_info": null,
            "sku": null,
            "vendor": null,
            "view_product_url": null,
            "price_total_cents": null
          }
        ]
       }',
			$this->invoice->toJson()
		);
	}

	public function testGet() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'get' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'get' )
		       ->with( $this->equalTo( '/orders/asdf1234/invoices/1234asdf' ) )
		       ->will( $this->returnValue( [
			       200,
			       [
				       'id'           => 'asdf1234',
				       'order_id'     => 'asdf1234',
				       'amount_cents' => 6000,
				       'currency'     => 'USD',
			       ],
			       ''
		       ] )
		       );

		$i = Invoice::get( 'asdf1234', '1234asdf', $client );

		$this->assertEquals( 'asdf1234', $i->id );
		$this->assertEquals( 'Apruve\Invoice', get_class( $i ) );
	}

	public function testSave() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'post' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'post' )
		       ->with( $this->equalTo( '/orders/asdf1234/invoices/' ),
			       $this->anything() )
		       ->will( $this->returnValue( [
			       201,
			       [
				       'id'           => 'asdf1234',
				       'order_id'     => 'asdf1234',
				       'amount_cents' => 6000,
				       'currency'     => 'USD',
			       ],
			       ''
		       ] )
		       );

		$invoice = new Invoice( [
			"amount_cents"   => 4300,
			"merchant_notes" => 'some notes',
			"order_id"       => 'asdf1234'
		], $client );
		$i       = $invoice->save();

		$this->assertEquals( 'asdf1234', $i->id );
		$this->assertEquals( 'Apruve\Invoice', get_class( $i ) );

	}

	protected function setUp() {
		Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->invoice = new Invoice( [
			"amount_cents"   => 6000,
			"currency"       => "USD",
			"merchant_notes" => null,
			"invoice_items"  => [
				[
					'title'          => 'test',
					'amount_cents'   => 100,
					'quantity'       => 1,
					'price_ea_cents' => 100

				]
			]
		] );

	}


}

