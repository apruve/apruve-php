<?php

require_once dirname(__FILE__) . '/../../src/Apruve/Client.php';

//use Apruve;

class ApruveClientTest extends PHPUnit_Framework_TestCase {
  protected $object;
  public static $AN_API_KEY = 'AnApiKey';

  protected function setUp() {
  }

  protected function tearDown() {
    
  }

  function testCreateAClient() {
    $client = Apruve\Client::init(self::$AN_API_KEY);
    $this->assertEquals(self::$AN_API_KEY, Apruve\Client::getInstance()->getApiKey());
  }
}


