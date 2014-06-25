<?php

namespace Apruve;

require_once 'CurlRequest.php';

class ClientStorage 
{
  public static $apiKey;
  public static $env;

  public static function setEnvironment(Environment $env)
  {
    static::$env = $env;
  }

  public static function setApiKey($apiKey)
  {
    static::$apiKey = $apiKey;
  }
}

class Client 
{
  private $apiKey;
  private $env;

  public function __construct($apiKey='', Environment $env=null)
  {
    if(empty($apiKey) or empty($env)) 
    {
      if (empty(ClientStorage::$apiKey) or empty(ClientStorage::$env))
      {
        throw new \InvalidArgumentException('Client must be initialized with init() first!');
      }
      else
      {
        $apiKey = ClientStorage::$apiKey;
        $env = ClientStorage::$env;
      }
    }
    $this->apiKey = $apiKey;
    $this->env = $env;
  }

  static function init($ApiKey, Environment $env) 
  {
    if ($env->getBaseUrl() != Environment::PROD and
        $env->getBaseUrl() != Environment::TEST and
        $env->getBaseUrl() != Environment::DEV)
    {
      throw new \InvalidArgumentException
        ('$env must be Apruve\Environment::PROD or Apruve\Environment::TEST to be valid.');

    }
    ClientStorage::setEnvironment($env);
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

  function getEnvironment() 
  {
    return $this->env;
  }

  protected function initCurl($url)
  {
    return new CurlRequest($url);
  }

  protected function restRequest($path)
  {
    $client = $this->initCurl($this->env->getApiUrl().$path);
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
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response, true), $client->error()];
    $client->close();
    return $ret;
  }

  public function get($path)
  {
    $client = $this->restRequest($path);
    $client->setOption(CURLOPT_RETURNTRANSFER, true);
    $response = $client->execute();
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response, true), $client->error()];
    $client->close();
    return $ret;
  }

  public function delete($path)
  {
    $client = $this->restRequest($path);
    $client->setOption(CURLOPT_CUSTOMREQUEST, "DELETE");
    $response = $client->execute();
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response, true), $client->error()];
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
    $ret = [$client->getInfo(CURLINFO_HTTP_CODE), json_decode($response, true), $client->error()];
    $client->close();
    return $ret;
  }


}
