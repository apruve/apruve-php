<?php

namespace Apruve;

require_once 'ApruveObject.php';

class PaymentItem extends ApruveObject {

  var $title;
  var $plan_code;
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
}
