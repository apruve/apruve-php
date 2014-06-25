<?php

namespace Apruve;

require_once 'ApruveObject.php';

class SubscriptionAdjustment extends ApruveObject
{

  protected static $SUBSCRIPTION_ADJUSTMENT_PATH = '/subscriptions/%s/adjustments/';

  var $id;
  var $subscription_id;
  var $status;
  var $title;
  var $amount_cents;
  var $quantity;
  var $price_ea_cents;
  var $merchant_notes;
  var $description;
  var $variant_info;
  var $sku;
  var $vendor;
  var $view_product_url;

  protected static $json_fields = [
    'subscription_id',
    'title',
    'amount_cents',
    'quantity',
    'price_ea_cents',
    'merchant_notes',
    'description',
    'variant_info',
    'sku',
    'vendor',
    'view_product_url',
  ];

  public static function get($subscription_id, $subscription_adjustment_id, $client=null)
  {
    if ($client == null)
    {
      $client = new Client();
    }
    $response = $client->get(sprintf(self::$SUBSCRIPTION_ADJUSTMENT_PATH, $subscription_id).$subscription_adjustment_id);

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
        self::$SUBSCRIPTION_ADJUSTMENT_PATH, $this->subscription_id), $this->toJson());
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
