<?php
// Shipment
namespace Apruve;

require_once 'ApruveObject.php';

class Shipment extends ApruveObject
{

    protected static $SHIPMENTS_PATH = '/invoices/%s/shipments/';
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

    public function __construct($shipment = [], $client = null)
    {
        if (array_key_exists('shipment_items', $shipment)) {
            foreach ( $shipment['shipment_items'] as $key => $shipment_item ) {
                if (is_array($shipment_item)) {
                    $shipment['shipment_items'][$key] = new ShipmentItem($shipment_item);
                }
            }
        }

        parent::__construct($shipment, $client);
    }

    public static function get($invoice_id, $client = null)
    {
        if ($client == null) {
            $client = new Client();
        }
        $response = $client->get(sprintf(self::$SHIPMENTS_PATH, $invoice_id));

        if ($response[0] == 200) {
            return new self($response[1], $client);
        } else {
            return $response[2];
        }
    }

    public function save()
    {
        $response = $this->client->post(
            sprintf(
                self::$SHIPMENTS_PATH, $this->invoice_id), $this->toJson());
        if ($response[0] == 201) {
            return new self($response[1], $this->client);
        } else {
            return $response[2];
        }
    }
}
