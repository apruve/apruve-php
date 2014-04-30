<?php

require_once dirname(__FILE__) . '/../Apruve/ApruveClient.php';

class ApruveClientTest extends PHPUnit_Framework_TestCase {
  protected $object;
  public static $AN_API_KEY = 'AnApiKey';

  protected function setUp() {
  }

  protected function tearDown() {
    
  }

  function testCreateAClient() {
    $client = ApruveClient.init(self::$AN_API_KEY);
    $this->assertEquals(self::$AN_API_KEY, ApruveClient.getInstance().getApiKey());
  }
}


