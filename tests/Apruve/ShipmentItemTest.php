<?php

require_once dirname(__FILE__).'/../../src/Apruve/ShipmentItem.php';

use Apruve\ShipmentItem;

class ShipmentItemTest extends PHPUnit_Framework_TestCase
{

    public function testPropertiesAreDefined()
    {
        $item_vars = get_class_vars(get_class($this->shipmentItem));

        $this->assertEquals(array_keys($item_vars), array(
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
            'merchant_shipment_id'
        ));
        $this->assertEquals(14, count($item_vars));
    }

    public function testToJsonString()
    {
        $this->assertJsonStringEqualsJsonString(
            '{
          "amount_cents": 34000,
          "invoice_id": "730cd103b8e8377359851498e5c34f7b",
          "shipper": "UPS",
          "tracking_number": "alphabetagamma",
          "shipped_at": "2017-07-01T10:12:27-05:00",
          "delivered_at": "2017-07-22T10:12:27-05:00",
          "merchant_notes": "i am notes",
          "uuid": "91ac96c0ffc9577ecb634ad726b1874e",
          "created_at": "2017-06-01T10:12:27-05:00",
          "currency": "USD",
          "tax_cents": 2601,
          "shipping_cents": 1000,
          "status": "fulfilled",
          "merchant_shipment_id": "merchantspecified"
       }', $this->shipmentItem->toJson()
        );
    }

    protected function setUp()
    {
        $this->shipmentItem = new ShipmentItem([
            'amount_cents'         => 34000,
            'invoice_id'           => '730cd103b8e8377359851498e5c34f7b',
            'shipper'              => 'UPS',
            'tracking_number'      => 'alphabetagamma',
            'shipped_at'           => '2017-07-01T10:12:27-05:00',
            'delivered_at'         => '2017-07-22T10:12:27-05:00',
            'merchant_notes'       => 'i am notes',
            'uuid'                 => '91ac96c0ffc9577ecb634ad726b1874e',
            'created_at'           => '2017-06-01T10:12:27-05:00',
            'currency'             => 'USD',
            'tax_cents'            => 2601,
            'shipping_cents'       => 1000,
            'status'               => 'fulfilled',
            'merchant_shipment_id' => 'merchantspecified',
        ], $this->getMockBuilder('Apruve\Client')
                ->setConstructorArgs(['a key', Apruve\Environment::DEV()])
                ->getMock()
        );
    }

    protected function tearDown()
    {

    }
}

