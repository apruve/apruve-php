<?php

namespace Apruve;

require_once 'ApruveObject.php';

class PaymentRequest extends ApruveObject
{
  protected static $PAYMENT_REQUESTS_PATH = '/payment_requests/';
  private static $FINALIZE_PATH = '/payment_requests/%s/finalize';

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
    $apiKey = $this->client->getApiKey();
    return hash("sha256", $apiKey.$this->toSecureString());
  }

  public function addLineItem($line_item)
  {
    if (get_class($line_item) == 'Apruve\LineItem')
    {
      return array_push($this->line_items, $line_item);
    }
    else
    {
      return false;
    }
  }

  public function get($payment_request_id, $client=null)
  {
    if ($client == null)
    {
      $client = new Client();
    }
    $response = $client->get(self::$PAYMENT_REQUESTS_PATH.$payment_request_id);

    if ($response[0] == 200)
    {
      $object = new self($response[1], $client);
      return $object;
    }
    else
    {
      return $response[2];
    }
  }

}
