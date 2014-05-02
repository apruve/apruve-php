<?php

require_once 'ApruveObject.php';

class PaymentRequest extends ApruveObject
{

  var $id;
  var $merchant_id;
  var $username;
  var $status;
  var $merchant_order_id;
  var $amount_cents;
  var $tax_cents;
  var $shipping_cents;
  var $currency;
  var $line_items = [];
  var $api_url;
  var $view_url;
  var $created_at;
  var $updated_at;

  protected static $hash_order = [
    'merchant_id',
    'amount_cents',
    'currency',
    'tax_cents',
    'shipping_cents',
  ];

  protected static $json_fields = [
    'id',
    'merchant_id',
    'merchant_order_id',
    'amount_cents',
    'tax_cents',
    'shipping_cents',
    'currency',
    'line_items',
  ];

  public function toSecureString()
  {
    $hashString = $this->toHashString();
    foreach($this->line_items as $line_item)
    {
      $hashString .= $line_item->toHashString();
    }
    return $hashString;
  }
     

  public function toSecureHash() 
  {
    $apiKey = ApruveClient::getInstance()->getApiKey();
    return hash("sha256", $apiKey.$this->toSecureString());
  }
}
