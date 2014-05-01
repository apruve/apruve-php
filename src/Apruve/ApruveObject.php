<?php

class ApruveObject {
 
  public function __construct($values=[]) 
  {
    foreach ($values as $name => $value)
    {
      $this->$name = $value;
    }
  }

  public function toHashString()
  {
    $ret = '';
    $called_class = get_called_class();
    foreach ($called_class::$hash_order as $key)
    {
      $ret .= $this->$key;
    }
    return $ret;
  }

  public function ToJsonString()
  {
    $jsonArr = [];
    $called_class = get_called_class();
    foreach ($called_class::$hash_order as $key)
    {
      $jsonArr[$key] = $this->$key;
    }
    return json_encode($jsonArr);

  }  
}
