<?php

namespace Apruve\ApruvePHP;

require_once 'ApruveObject.php';

class LineItem extends ApruveObject {

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

  protected static $hash_order = [
    'title',
    'plan_code',
    'amount_cents',
    'price_ea_cents',
    'quantity',
    'merchant_notes',
    'description',
    'variant_info', 
    'sku',
    'vendor', 
    'view_product_url',
  ];

  protected static $json_fields = [
    'title',
    'plan_code',
    'amount_cents',
    'price_ea_cents',
    'quantity',
    'merchant_notes',
    'description',
    'variant_info', 
    'sku',
    'vendor', 
    'view_product_url',
  ];
}
