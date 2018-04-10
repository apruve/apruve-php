<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/InvoiceReturn.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Client.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';

use Apruve\InvoiceReturn;

class InvoiceReturnTest extends \PHPUnit\Framework\TestCase {

	public function testPropertiesAreDefined() {
		$vars = get_class_vars( get_class( $this->invoiceReturn ) );

		$this->assertEquals( array_keys( $vars ), [
			'invoice_id',
			'amount_cents',
			'currency',
			'reason',
		] );
		$this->assertEquals( 4, count( $vars ) );

	}

	public function testToJson() {
		$this->assertJsonStringEqualsJsonString(
			'{
							"invoice_id": "asdf1234",
							"amount_cents": 6000,
							"currency": "USD",
							"reason": "OTHER"
						}',
			$this->invoiceReturn->toJson()
		);
	}

	public function testGet() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'get' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'get' )
		       ->with( $this->equalTo( '/invoices/asdf1234/invoice_returns/8764ase' ) )
		       ->will( $this->returnValue( [
			       200,
			       [
				       'id'           => '8764asew',
				       'invoice_id'   => 'asdf1234',
				       'amount_cents' => 6000,
				       'currency'     => 'USD',
			       ],
			       ''
		       ] )
		       );

		$r = InvoiceReturn::get( 'asdf1234', '8764ase', $client );

		$this->assertEquals( '8764asew', $r->id );
		$this->assertEquals( 'Apruve\InvoiceReturn', get_class( $r ) );
	}

	public function testSave() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'post' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'post' )
		       ->with( $this->equalTo( '/invoices/asdf1234/invoice_returns/' ),
			       $this->anything() )
		       ->will( $this->returnValue( [
			       201,
			       [
				       'id'           => '8764asew',
				       'invoice_id'   => 'asdf1234',
				       'amount_cents' => 6000,
				       'currency'     => 'USD',
				       'reason'       => 'OTHER'
			       ],
			       ''
		       ] )
		       );

		$invoiceReturn = new InvoiceReturn( [
			"amount_cents"   => 4300,
			"merchant_notes" => 'some notes',
			"invoice_id"     => 'asdf1234'
		], $client );
		$ir            = $invoiceReturn->save();

		$this->assertEquals( 'asdf1234', $ir->invoice_id );
		$this->assertEquals( 'Apruve\InvoiceReturn', get_class( $ir ) );

	}

	protected function setUp() {
		Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->invoiceReturn = new InvoiceReturn( [
			"amount_cents" => 6000,
			"invoice_id"   => 'asdf1234',
			"currency"     => "USD",
			"reason"       => "OTHER",
		] );

	}


}

