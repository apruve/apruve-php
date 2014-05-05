<?php

namespace Apruve\ApruvePHP;

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

  public function toJsonArray()
  {
    $jsonArr = [];
    $called_class = get_called_class();
    foreach ($called_class::$json_fields as $key)
    {
      if (gettype($this->$key) == "array")
      {
        $jsonArr[$key] = [];
        foreach($this->$key as $item)
        {
          array_push($jsonArr[$key], $item->toJsonArray());
        }
      }
      else
      {
        $jsonArr[$key] = $this->$key;
      }
    }
    return $jsonArr;
  }

  public function ToJsonString()
  {
    return json_encode($this->toJsonArray());
  }  
}
