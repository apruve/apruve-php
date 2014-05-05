<?php

namespace Apruve;

class Client 
{
  private static $instance;
  private $ApiKey;
  private $baseUrl;
  public const $apiPath = '/api/v3';

  private function __construct()
  {

  }

  static function getInstance() 
  {
    if(self::$instance === NULL) 
    {
      throw new \InvalidArgumentException('Client must be initialized with init() first!');
    }
    return self::$instance;
  }

  static function init($ApiKey, $env) 
  {
    if ($env != Environment::PROD and
        $env != Environment::TEST and
        $env != Environment::DEV)
    {
      throw new \InvalidArgumentException
        ('$env must be Apruve\Environment::PROD or Apruve\Environment::TEST to be valid.');

    }
    self::$instance = new Client();
    self::$instance->baseUrl = $env;
    self::$instance->setApiKey($ApiKey);
    return self::$instance;
  }

  function getApiKey() 
  {
    return $this->ApiKey;
  }

  function setApiKey($ApiKey) 
  {
    $this->ApiKey = $ApiKey;
  }

  function getBaseUrl() 
  {
    return $this->baseUrl;
  }

  protected function restRequest($path)
  {
    $client = curl_init($this->baseUrl.$this->apiPath.$path);
    curl_setopt($client, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      "Apruve-Api-Key: $this->ApiKey",]);
    return $client;
  }

  public function post($path, $payload)
  {
    $client = restRequest($path);
    curl_setopt($client, CURLOPT_POST, true);
    curl_setopt($client, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($client);
    $ret = [curl_getinfo($client, CURLINFO_HTTP_CODE), $response, curl_error($client)];
    curl_close($client);
    return $ret;
    


  }


}


