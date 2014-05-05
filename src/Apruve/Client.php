<?php

namespace Apruve;

class Client {

  private static $instance;
  private $ApiKey;

  private function __construct()
  {
  }

  static function getInstance() {
    if(self::$instance === NULL) {
      self::$instance = new Client();
    }
    return self::$instance;
  }

  static function init($ApiKey) {
    $apruveClient = self::getInstance();
    $apruveClient->setApiKey($ApiKey);
    return $apruveClient;
  }

  function getApiKey() {
    return $this->ApiKey;
  }

  function setApiKey($ApiKey) {
    $this->ApiKey = $ApiKey;
  }

}


?>
