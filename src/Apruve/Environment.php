<?php

namespace Apruve;

class Environment 
{
  const PROD = 'https://www.apruve.com';
  const TEST = 'https://test.apruve.com';
  const DEV = 'http://localhost:3000';

  private $baseUrl;

  private function __construct($environment)
  {
    $this->baseUrl = $environment;  
  }

  public static function PROD()
  {
    return new self(self::PROD);
  }

  public static function TEST()
  {
    return new self(self::TEST);
  }

  public static function DEV()
  {
    return new self(self::DEV);
  }

  public function getJsUrl()
  {
    return $this->baseUrl.'/js/apruve.js';
  }

  public function getApiUrl()
  {
    return $this->baseUrl.'/api/v3';
  }

  public function getBaseUrl()
  {
    return $this->baseUrl;
  }


}
