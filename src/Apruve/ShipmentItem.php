<?php
// Shipment Item
namespace Apruve;

require_once 'ApruveObject.php';

class ShipmentItem extends ApruveObject {

	protected static $hash_order = [
		'title',
		'amount_cents',
		'price_total_cents',
		'quantity',
		'price_ea_cents',
		'merchant_notes',
		'description',
		'variant_info',
		'sku',
		'vendor',
		'sku',
		'view_product_url',
		'shipping_cents',
		'tax_cents',
		'price_total_cents',
		'currency',
	];
	protected static $json_fields = [
		'title',
		'amount_cents',
		'price_total_cents',
		'quantity',
		'price_ea_cents',
		'merchant_notes',
		'description',
		'variant_info',
		'sku',
		'vendor',
		'sku',
		'view_product_url',
		'shipping_cents',
		'tax_cents',
		'price_total_cents',
		'currency',
	];
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
	var $shipping_cents;
	var $tax_cents;
	var $price_total_cents;
	var $currency;
}
