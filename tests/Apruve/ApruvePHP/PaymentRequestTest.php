<?php

require_once dirname(__FILE__) . '/../../../src/Apruve/ApruvePHP/PaymentRequest.php';
require_once dirname(__FILE__) . '/../../../src/Apruve/ApruvePHP/LineItem.php';
require_once dirname(__FILE__) . '/../../../src/Apruve/ApruvePHP/ApruveClient.php';

use Apruve\ApruvePHP\PaymentRequest;
use Apruve\ApruvePHP\LineItem;
use Apruve\ApruvePHP\ApruveClient;

class PaymentRequestTest extends PHPUnit_Framework_TestCase
{

  protected function setUp()
  {
    $this->pr = new PaymentRequest([
      'id' => 'id',
      'merchant_id' => 'asdf1234',
      'merchant_order_id' => 'order1234',
      'amount_cents' => 6000,
      'tax_cents' => 500,
      'shipping_cents' => 1000,
      'currency' => 'USD',
      'line_items' => [
        new LineItem([
          'title' => 'a title',
          'amount_cents' => 4500,
        ])
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
      'line_items',
      'api_url',
      'view_url',
      'created_at',
      'updated_at',
    ]);
    $this->assertEquals(14, count($vars));
    
  }

  public function testToHashString()
  {
    $this->assertEquals(
      'asdf12346000USD5001000',
      $this->pr->toHashString()
    );
  }

  public function testToJsonString()
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
       $this->pr->toJsonString()
     );
  }

  public function testToSecureString() 
  {
    $this->assertEquals(
      'asdf12346000USD5001000a title4500',
      $this->pr->toSecureString()
    );
  }

  public function testToSecureHash()
  {
    ApruveClient::getInstance()->setApiKey('a key');
    $this->assertEquals(
      hash('sha256', 'a keyasdf12346000USD5001000a title4500'),
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

  public function addLineItemOnlyAllowsLineItems()
  {
    $this->setExpectedException('InvalidArgumentException');
    $this->assertEquals(false,$this->pr->addLineItem(
      ['title' => 'a title','amount_cents' => 423,]));
  }


      

}

