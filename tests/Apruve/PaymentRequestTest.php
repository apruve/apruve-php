<?php

require_once dirname(__FILE__) . '/../../src/Apruve/PaymentRequest.php';
require_once dirname(__FILE__) . '/../../src/Apruve/LineItem.php';
require_once dirname(__FILE__) . '/../../src/Apruve/Client.php';

use Apruve\PaymentRequest;
use Apruve\LineItem;

class PaymentRequestTest extends PHPUnit_Framework_TestCase
{

  protected function setUp()
  {
    Apruve\Client::init('a key', Apruve\Environment::DEV());
    $this->pr = new PaymentRequest([
      'id' => 'id',
      'merchant_id' => 'asdf1234',
      'merchant_order_id' => 'order1234',
      'amount_cents' => 6000,
      'tax_cents' => 500,
      'shipping_cents' => 1000,
      'currency' => 'USD',
      'expire_at' => '2014-07-15T10:12:27-05:00',
      'line_items' => [
        [
          'title' => 'a title',
          'amount_cents' => 4500,
        ]
      ],
    ]);
  }

  public function testPropertiesAreDefined()
  {
    $vars = get_class_vars(get_class($this->pr));

    $this->assertEquals(array_keys($vars),[
      'id',
      'merchant_id',
      'username',
      'status',
      'merchant_order_id',
      'amount_cents',
      'tax_cents',
      'shipping_cents',
      'currency',
      'expire_at',
      'line_items',
      'api_url',
      'view_url',
      'created_at',
      'updated_at',
    ]);
    $this->assertEquals(15, count($vars));
    
  }

  public function testToHashString()
  {
    $this->assertEquals(
      'asdf12346000USD50010002014-07-15T10:12:27-05:00',
      $this->pr->toHashString()
    );
  }

  public function testToJson()
  {
    $this->assertJsonStringEqualsJsonString(
      '{
        "id": "id",
        "merchant_id": "asdf1234",
        "merchant_order_id": "order1234",
        "amount_cents": 6000,
        "tax_cents": 500,
        "shipping_cents": 1000,
        "currency": "USD",
        "expire_at": "2014-07-15T10:12:27-05:00",
        "line_items": [
          {
            "title": "a title",
            "amount_cents": 4500,
            "plan_code": null,
            "price_ea_cents": null,
            "quantity": null,
            "merchant_notes": null,
            "description": null,
            "variant_info": null,
            "sku": null,
            "vendor": null,
            "view_product_url": null
          }
        ]
       }',
       $this->pr->toJson()
     );
  }

  public function testToSecureString() 
  {
    $this->assertEquals(
      'asdf12346000USD50010002014-07-15T10:12:27-05:00a title4500',
      $this->pr->toSecureString()
    );
  }

  public function testToSecureHash()
  {
    Apruve\Client::init('a key', Apruve\Environment::DEV());
    $this->assertEquals(
      hash('sha256', 'a keyasdf12346000USD50010002014-07-15T10:12:27-05:00a title4500'),
      $this->pr->toSecureHash()
    );
  }

  public function testAddLineItem()
  {
    $item_count = count($this->pr->line_items);
    $this->pr->addLineItem(
      new LineItem([
        'title' => 'A new title',
        'amount_cents' => 4521,
      ])
    );
    $this->assertEquals($item_count + 1, count($this->pr->line_items));
    
  }

  public function testAddLineItemOnlyAllowsLineItems()
  {
    $this->assertEquals(false,$this->pr->addLineItem(new PaymentRequest));
  }


  public function testGet()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['get'])
      ->getMock();
    $client->expects($this->Once())
      ->method('get')
      ->with($this->equalTo('/payment_requests/asdf1234'))
      ->will($this->returnValue([
        200,
        [
          'id' => 'asdf1234',
          'merchant_id' => 'asdf1234',
          'merchant_order_id' => 'order1234',
          'amount_cents' => 6000,
          'tax_cents' => 500,
          'shipping_cents' => 1000,
          'currency' => 'USD',
        ],
        ''])
      );

    $pr = PaymentRequest::get('asdf1234', $client);

    $this->assertEquals('asdf1234', $pr->id);
    $this->assertEquals('Apruve\PaymentRequest', get_class($pr));
  }

  public function testUpdate()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['put'])
      ->getMock();
    $client->expects($this->Once())
      ->method('put')
      ->with($this->equalTo('/payment_requests/asdf1234'))
      ->will($this->returnValue([
        200,
        [
          'id' => 'asdf1234',
          'merchant_id' => 'asdf1234',
          'merchant_order_id' => 'order1234',
          'amount_cents' => 6000,
          'tax_cents' => 500,
          'shipping_cents' => 1000,
          'currency' => 'USD',
        ],
        ''])
      );

    $pr = new PaymentRequest([
          'id' => 'asdf1234',
          'merchant_id' => 'asdf1234',
          'merchant_order_id' => 'order1234',
          'amount_cents' => 6000,
          'currency' => 'USD'], $client);
    $pr->setShippingCents(500);
    $pr->setTaxCents(439);
    $pr->setAmountCents(6199);

    $pr = $pr->update();

    $this->assertEquals(true, $pr);
  }
      

}

