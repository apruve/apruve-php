<?php

require_once dirname(__FILE__) . '/../../src/Apruve/Client.php';
require_once dirname(__FILE__) . '/../../src/Apruve/Environment.php';

class ApruveClientTest extends PHPUnit_Framework_TestCase {
  public static $AN_API_KEY = 'AnApiKey';

  protected function setUp() 
  {
    $reflection = new \ReflectionClass('Apruve\Client');
    $clientInstance = $reflection->getProperty('instance');
    $clientInstance->setAccessible(true);
    $clientInstance->setValue(NULL);
  }

  protected function tearDown() 
  {
    
  }

  public function testCreateAClient() 
  {
    $client = Apruve\Client::init(self::$AN_API_KEY, Apruve\Environment::DEV);
    $this->assertEquals(self::$AN_API_KEY, Apruve\Client::getInstance()->getApiKey());
  }
  
  public function testGetUninstantiatedClient()
  {
    $this->setExpectedException('InvalidArgumentException');
    $client = Apruve\Client::getInstance();
    $this->assertEquals(null, $client);

  }

  public function testIncorrectEnvironment() 
  {
    $this->setExpectedException('InvalidArgumentException');
    $client = Apruve\Client::init(self::$AN_API_KEY, 'Some STring');
  }
  
  public function testBaseUrlSet()
  {
    $client = Apruve\Client::init(self::$AN_API_KEY, Apruve\Environment::DEV);
    $this->assertEquals(Apruve\Environment::DEV, $client->getBaseUrl());

  }

}


