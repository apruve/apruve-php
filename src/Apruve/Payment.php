<?php

namespace Apruve;

require_once 'ApruveObject.php';

class Payment extends ApruveObject
{

  protected static $PAYMENTS_PATH = '/payment_requests/%s/payments/';

  var $id;
  var $payment_request_id;
  var $status;
  var $amount_cents;
  var $currency;
  var $merchant_notes;
  var $api_url;
  var $view_url;
  var $created_at;
  var $updated_at;
  var $payment_items = [];

  protected static $hash_order = [];

  protected static $json_fields = [
    'amount_cents',
    'currency',
    'merchant_notes',
    'payment_items',
  ];

  public function __construct($payment=[], $client=null)
  {
    if (array_key_exists('payment_items', $payment))
    {
      foreach($payment['payment_items'] as $key => $payment_item)
      {
        if (is_array($payment_item))
        {
          $payment['payment_items'][$key] = new PaymentItem($payment_item);
        }
      }
    }

    parent::__construct($payment, $client);
  }

  public static function get($payment_request_id, $payment_id, $client=null)
  {
    if ($client == null)
    {
      $client = new Client();
    }
    $response = $client->get(sprintf(self::$PAYMENTS_PATH, $payment_request_id).$payment_id);

    if ($response[0] == 200)
    {
      return new self($response[1], $client);
    }
    else
    {
      return $response[2];
    }
  }

  public function save()
  {
    $response = $this->client->post(
      sprintf(
        self::$PAYMENTS_PATH, $this->payment_request_id), $this->toJson());
    if ($response[0] == 201)
    {
      return new self($response[1], $this->client);
    }
    else
    {
      return $response[2];
    }
  }

}
