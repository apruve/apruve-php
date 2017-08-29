<?php
// Order Item
namespace Apruve;

require_once 'ApruveObject.php';

class OrderItem extends ApruveObject {

	protected static $hash_order = [
		'title',
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
	var $id;
	var $order_id;
	var $title;
	var $price_total_cents;
	var $quantity;
	var $price_ea_cents;
	var $merchant_notes;
	var $description;
	var $variant_info;
	var $sku;
	var $vendor;
	var $view_product_url;
}
