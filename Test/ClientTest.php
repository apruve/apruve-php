<?php

require_once dirname(__FILE__) . '/../Apruve/Client.php';

class ClientTest extends PHPUnit_Framework_TestCase {
  protected $object;

  protected function setUp() {
    $this->object = new Client;
  }

  protected function tearDown() {
    
  }

  function testDummyPassingTest() {
    $this->assertTrue(false);
  }
}


