<?php
// Shipment Item
namespace Apruve;

require_once 'ApruveObject.php';

class ShipmentItem extends ApruveObject
{

    protected static $hash_order = [
        'amount_cents',
        'invoice_id',
        'shipper',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'merchant_notes',
        'uuid',
        'created_at',
        'currency',
        'tax_cents',
        'shipping_cents',
        'merchant_shipment_id',
    ];
    protected static $json_fields = [
        'amount_cents',
        'invoice_id',
        'shipper',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'merchant_notes',
        'uuid',
        'created_at',
        'currency',
        'tax_cents',
        'shipping_cents',
        'status',
        'merchant_shipment_id',
    ];
    var $amount_cents;
    var $invoice_id;
    var $shipper;
    var $tracking_number;
    var $shipped_at;
    var $delivered_at;
    var $merchant_notes;
    var $uuid;
    var $created_at;
    var $currency;
    var $tax_cents;
    var $shipping_cents;
    var $status;
    var $merchant_shipment_id;
}
