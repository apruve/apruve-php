<?php

require_once dirname(__FILE__) . '/../../src/Apruve/Payment.php';
require_once dirname(__FILE__) . '/../../src/Apruve/Client.php';

use Apruve\Payment;

class PaymentTest extends PHPUnit_Framework_TestCase
{

  protected function setUp()
  {
    Apruve\Client::init('a key', Apruve\Environment::DEV());
    $this->payment = new Payment([
      "amount_cents" => 6000,
      "currency" => "USD",
      "merchant_notes" => null,
      "payment_items" => [
        [
          'title' => 'test',
          'amount_cents' => 100,
          'quantity' => 1,
          'price_ea_cents' => 100
        ]
      ]
    ]);

  }

  public function testPropertiesAreDefined()
  {
    $vars = get_class_vars(get_class($this->payment));

    $this->assertEquals(array_keys($vars),[
      'id',
      'payment_request_id',
      'status',
      'amount_cents',
      'currency',
      'merchant_notes',
      'api_url',
      'view_url',
      'created_at',
      'updated_at',
      'payment_items',
    ]);
    $this->assertEquals(11, count($vars));
    
  }

  public function testToJson()
  {
    $this->assertJsonStringEqualsJsonString(
      '{
        "amount_cents": 6000,
        "currency": "USD",
        "merchant_notes": null,
        "payment_items": [
          {
            "title": "test",
            "amount_cents": 100,
            "quantity" : 1,
            "price_ea_cents": 100,
            "merchant_notes": null,
            "description": null,
            "variant_info": null,
            "sku": null,
            "vendor": null,
            "view_product_url": null
          }
        ]
       }',
       $this->payment->toJson()
     );
  }

  public function testGet()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['get'])
      ->getMock();
    $client->expects($this->Once())
      ->method('get')
      ->with($this->equalTo('/payment_requests/asdf1234/payments/1234asdf'))
      ->will($this->returnValue([
        200,
        [
          'id' => 'asdf1234',
          'payment_request_id' => 'asdf1234',
          'amount_cents' => 6000,
          'currency' => 'USD',
        ],
        ''])
      );

    $pr = Payment::get('asdf1234', '1234asdf', $client);

    $this->assertEquals('asdf1234', $pr->id);
    $this->assertEquals('Apruve\Payment', get_class($pr));
  }

  public function testSave()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['post'])
      ->getMock();
    $client->expects($this->Once())
      ->method('post')
      ->with($this->equalTo('/payment_requests/asdf1234/payments/'),
        $this->anything())
      ->will($this->returnValue([
        201,
        [
          'id' => 'asdf1234',
          'payment_request_id' => 'asdf1234',
          'amount_cents' => 6000,
          'currency' => 'USD',
        ],
        ''])
      );

    $payment = new Payment([
      "amount_cents" => 4300,
      "merchant_notes" => 'some notes',
      "payment_request_id" => 'asdf1234'], $client);
    $pr = $payment->save();

    $this->assertEquals('asdf1234', $pr->id);
    $this->assertEquals('Apruve\Payment', get_class($pr));

  }
      

}

