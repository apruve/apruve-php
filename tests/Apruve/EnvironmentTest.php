<?php

require_once dirname(__FILE__) . '/../../src/Apruve/Environment.php';

class ApruveEnvironmentTest extends PHPUnit_Framework_TestCase 
{

  protected function setUp()
  {
  }

  public function testURLs()
  {
    $this->assertEquals('https://www.apruve.com', Apruve\Environment::PROD);
    $this->assertEquals('https://test.apruve.com', Apruve\Environment::TEST);
    $this->assertEquals('http://localhost:3000', Apruve\Environment::DEV);

  }
}
