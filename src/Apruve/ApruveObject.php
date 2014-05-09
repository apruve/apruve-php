<?php

namespace Apruve;

abstract class ApruveObject {

  protected $client;
  
  public function __construct($values=[], $client=null) 
  {
    if ($client == null)
    {
      $this->client = new Client();
    }
    else
    {
      $this->client = $client;
    }
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

  public function toJson()
  {
    return json_encode($this->toJsonArray());
  }  
}
