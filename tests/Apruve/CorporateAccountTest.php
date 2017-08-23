<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/CorporateAccount.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Client.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';

use Apruve\CorporateAccount;

class CorporateAccountTest extends PHPUnit_Framework_TestCase {

	public function testPropertiesAreDefined() {
		$vars = get_class_vars( get_class( $this->corporate_account ) );

		$this->assertEquals( array_keys( $vars ), [
			'id',
			'customer_uuid',
			'merchant_uuid',
			'type',
			'payment_term_strategy_name',
			'name',
			'authorized_buyers',
		] );
		$this->assertEquals( 7, count( $vars ) );

	}

	public function testToJson() {
		$this->assertJsonStringEqualsJsonString(
			'{
		"id": "asdf1234",
        "customer_uuid": "b76ac505389e7814eb10fb4fdc33a50b",
        "merchant_uuid": "5ca2ab51d10b490cba7b22934c9fe913",
        "type": "corporate",
        "payment_term_strategy_name": "Net30",
        "name": "MyCorporateAccount",
        "authorized_buyers": [
          {
            "id": "d9e10e59140513b61998e292088c8194",
            "name": "Corporate Corbin",
            "email" : "corporateuser@apruve.com"
          }
        ]
       }',
			$this->corporate_account->toJson()
		);
	}

	public function testGet() {
		$client = $this->getMockBuilder( 'Apruve\Client' )
		               ->setMethods( [ 'get' ] )
		               ->getMock();
		$client->expects( $this->Once() )
		       ->method( 'get' )
		       ->with( $this->equalTo( '/merchants/asdf1234/corporate_accounts?email=corporateuser@apruve.com' ) )
		       ->will( $this->returnValue( [
			       200,
			       [
				       'id'                         => 'asdf1234',
				       'customer_uuid'              => 'b76ac505389e7814eb10fb4fdc33a50b',
				       'merchant_uuid'              => '5ca2ab51d10b490cba7b22934c9fe913',
				       'type'                       => 'corporate',
				       'payment_term_strategy_name' => 'Net30',
				       'name'                       => 'MyCorporateAccount',
			       ],
			       ''
		       ] )
		       );

		$i = CorporateAccount::get( 'asdf1234', 'corporateuser@apruve.com', $client );

		$this->assertEquals( 'asdf1234', $i->id );
		$this->assertEquals( 'Apruve\CorporateAccount', get_class( $i ) );
	}

	protected function setUp() {
		Apruve\Client::init( 'a key', Apruve\Environment::DEV() );
		$this->corporate_account = new CorporateAccount( [
			'id'                         => 'asdf1234',
			'customer_uuid'              => 'b76ac505389e7814eb10fb4fdc33a50b',
			'merchant_uuid'              => '5ca2ab51d10b490cba7b22934c9fe913',
			'type'                       => 'corporate',
			'payment_term_strategy_name' => 'Net30',
			'authorized_buyers'          => [
				[
					'id'    => 'd9e10e59140513b61998e292088c8194',
					'name'  => 'Corporate Corbin',
					'email' => 'corporateuser@apruve.com'
				]
			],
			'name'                       => 'MyCorporateAccount'
		] );
	}


}

