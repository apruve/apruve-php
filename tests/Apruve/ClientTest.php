<?php

require_once dirname( __FILE__ ) . '/../../src/Apruve/Client.php';
require_once dirname( __FILE__ ) . '/../../src/Apruve/Environment.php';

class ApruveClientTest extends \PHPUnit\Framework\TestCase {
	public static $AN_API_KEY = 'AnApiKey';

	public function testGetEnvironment() {
		$env    = Apruve\Environment::DEV();
		$client = Apruve\Client::init( 'a key', $env );
		$this->assertEquals( $env, $client->getEnvironment() );
	}

	public function testPost() {
		$httpStub = $this->getMockBuilder( 'Apruve\CurlRequest' )
		                 ->setMethods( [ 'execute', 'setOption' ] )
		                 ->setConstructorArgs( [ 'http://localhost:3000/api/v3/blah' ] )
		                 ->getMock();
		$httpStub->expects( $this->atLeastOnce() )
		         ->method( 'setOption' )
		         ->withConsecutive(
			         [ $this->equalTo( CURLOPT_HTTPHEADER ), $this->anything() ],
			         [ $this->equalTo( CURLOPT_POST ), $this->equalTo( true ) ],
			         [ $this->equalTo( CURLOPT_POSTFIELDS ), $this->anything() ],
			         [ $this->equalTo( CURLOPT_RETURNTRANSFER ), $this->equalTo( true ) ]
		         );

		$client = $this->getMockClient( $httpStub );

		$response = $client->post( '/blah', '{"payload": "blah"}' );

		$this->assertEquals( 3, count( $response ) );
	}

	public function getMockClient( $httpStub ) {
		$mock = $this->getMockBuilder( 'Apruve\Client' )
		             ->setMethods( [ 'initCurl' ] )
		             ->setConstructorArgs( [ static::$AN_API_KEY, Apruve\Environment::DEV() ] )
		             ->getMock();
		$mock->expects( $this->atLeastOnce() )
		     ->method( 'initCurl' )
		     ->will( $this->returnValue( $httpStub ) );

		return $mock;
	}

	public function testPut() {
		$httpStub = $this->getMockBuilder( 'Apruve\CurlRequest' )
		                 ->setMethods( [ 'execute', 'setOption' ] )
		                 ->setConstructorArgs( [ 'http://localhost:3000/api/v4/blah' ] )
		                 ->getMock();
		$httpStub->expects( $this->atLeastOnce() )
		         ->method( 'setOption' )
		         ->withConsecutive(
			         [ $this->equalTo( CURLOPT_HTTPHEADER ), $this->anything() ],
			         [ $this->equalTo( CURLOPT_CUSTOMREQUEST ), $this->equalTo( 'PUT' ) ],
			         [ $this->equalTo( CURLOPT_POSTFIELDS ), $this->anything() ],
			         [ $this->equalTo( CURLOPT_RETURNTRANSFER ), $this->equalTo( true ) ]
		         );

		$client = $this->getMockClient( $httpStub );

		$response = $client->put( '/blah', '{"payload": "blah"}' );

		$this->assertEquals( 3, count( $response ) );
	}

	public function testGet() {
		$httpStub = $this->getMockBuilder( 'Apruve\CurlRequest' )
		                 ->setMethods( [ 'execute', 'setOption' ] )
		                 ->setConstructorArgs( [ 'http://localhost:3000/api/v4/blah' ] )
		                 ->getMock();
		$httpStub->expects( $this->atLeastOnce() )
		         ->method( 'setOption' )
		         ->withConsecutive(
			         [ $this->equalTo( CURLOPT_HTTPHEADER ), $this->anything() ],
			         [ $this->equalTo( CURLOPT_RETURNTRANSFER ), $this->equalTo( true ) ]
		         );

		$client = $this->getMockClient( $httpStub );

		$response = $client->get( '/blah' );

		$this->assertEquals( 3, count( $response ) );
	}

	public function testDelete() {
		$httpStub = $this->getMockBuilder( 'Apruve\CurlRequest' )
		                 ->setMethods( [ 'execute', 'setOption' ] )
		                 ->setConstructorArgs( [ 'http://localhost:3000/api/v4/blah' ] )
		                 ->getMock();
		$httpStub->expects( $this->atLeastOnce() )
		         ->method( 'setOption' )
		         ->withConsecutive(
			         [ $this->equalTo( CURLOPT_HTTPHEADER ), $this->anything() ],
			         [ $this->equalTo( CURLOPT_CUSTOMREQUEST ), $this->equalTo( 'DELETE' ) ],
			         [ $this->equalTo( CURLOPT_RETURNTRANSFER ), $this->equalTo( true ) ]
		         );

		$client = $this->getMockClient( $httpStub );

		$response = $client->delete( '/blah' );

		$this->assertEquals( 3, count( $response ) );
	}

	public function testCreateAClient() {
		$client = $this->initClient();
		$this->assertEquals( self::$AN_API_KEY, $client->getApiKey() );
	}

	public function initClient() {
		return Apruve\Client::init( self::$AN_API_KEY, Apruve\Environment::DEV() );
	}

	public function testGetUninstantiatedClient() {
		$this->expectException( 'InvalidArgumentException' );
		$client = new Apruve\Client();
	}

	public function testBaseUrlSet() {
		$client = $this->initClient();
		$this->assertEquals( Apruve\Environment::DEV, $client->getEnvironment()->getBaseUrl() );
	}

	protected function setUp() {
		Apruve\ClientStorage::$apiKey = null;
		Apruve\ClientStorage::$env    = null;
	}

	protected function tearDown() {

	}

}


