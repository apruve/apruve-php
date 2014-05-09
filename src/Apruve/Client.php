<?php

namespace Apruve;

require_once 'CurlRequest.php';

class ClientStorage 
{
  public static $apiKey;
  public static $baseUrl;

  public static function setBaseUrl($baseUrl)
  {
    static::$baseUrl = $baseUrl;
  }

  public static function setApiKey($apiKey)
  {
    static::$apiKey = $apiKey;
  }
}

class Client 
{
  private $apiKey;
  private $baseUrl;
  const apiPath = '/api/v3';

  public function __construct($apiKey='', $baseUrl='')
  {
    if(empty($apiKey) or empty($baseUrl)) 
    {
      if (empty(ClientStorage::$apiKey) or empty(ClientStorage::$baseUrl))
      {
        throw new \InvalidArgumentException('Client must be initialized with init() first!');
      }
      else
      {
        $apiKey = ClientStorage::$apiKey;
        $baseUrl = ClientStorage::$baseUrl;
      }
    }
    $this->apiKey = $apiKey;
    $this->baseUrl = $baseUrl;
  }

  static function init($ApiKey, $baseUrl) 
  {
    if ($baseUrl != Environment::PROD and
        $baseUrl != Environment::TEST and
        $baseUrl != Environment::DEV)
    {
      throw new \InvalidArgumentException
        ('$env must be Apruve\Environment::PROD or Apruve\Environment::TEST to be valid.');

    }
    ClientStorage::setBaseUrl($baseUrl);
    ClientStorage::setApiKey($ApiKey);
    return new Client();
  }

  function getApiKey() 
  {
    return $this->apiKey;
  }

  function setApiKey($ApiKey) 
  {
    $this->ApiKey = $ApiKey;
  }

  function getBaseUrl() 
  {
    return $this->baseUrl;
  }

  protected function initCurl($url)
  {
    return new CurlRequest($url);
  }

  protected function restRequest($path)
  {
    $client = $this->initCurl($this->baseUrl.Client::apiPath.$path);
    $client->setOption(CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      "Apruve-Api-Key: $this->apiKey",
    ]);
    return $client;
  }

  public function post($path, $payload)
  {
    $client = $this->restRequest($path);
    $client->setOption(CURLOPT_POST, true);
    $client->setOption(CURLOPT_POSTFIELDS, $payload);
    $client->setOption(CURLOPT_RETURNTRANSFER, true);
    $response = $client->execute();
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response), $client->error()];
    $client->close();
    return $ret;
  }

  public function get($path)
  {
    $client = $this->restRequest($path);
    $client->setOption(CURLOPT_RETURNTRANSFER, true);
    $response = $client->execute();
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response), $client->error()];
    $client->close();
    return $ret;
  }

  public function put($path, $payload)
  {
    $client = $this->restRequest($path);
    $client->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
    $client->setOption(CURLOPT_POSTFIELDS, $payload);
    $client->setOption(CURLOPT_RETURNTRANSFER, true);
    $response = $client->execute();
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response), $client->error()];
    $client->close();
    return $ret;
  }


}
