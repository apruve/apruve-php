<?php

class ApruveClient {

  private static $instance;
  private $ApiKey;

  private function __construct()
  {
  }

  static function getInstance() {
    if(self::$instance === NULL) {
      self::$instance = new ApruveClient();
    }
    return self::$instance;
  }

  static function init($ApiKey) {
    $apruveClient = getInstance();
    $apruveClient->setApiKey($ApiKey);
  }

  function getApiKey() {
    return $this->ApiKey;
  }

  function setApiKey($ApiKey) {
    $this->ApiKey = $ApiKey;
  }

}


?>
