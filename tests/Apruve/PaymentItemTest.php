<?php

require_once dirname(__FILE__) . '/../../src/Apruve/PaymentItem.php';

use Apruve\PaymentItem;

class PaymentItemTest extends PHPUnit_Framework_TestCase {

  protected function setUp() {
    $this->item = new PaymentItem([
      'title' => 'A title',
      'amount_cents' => 3400,
      'quantity' => 1,
      'price_ea_cents' => 3400,
      'merchant_notes' => 'some notes.',
      'description' => 'a description.',
      'variant_info' => 'some variation.',
      'sku' => 'a sku',
      'vendor' => 'ACME',
      'view_product_url' => 'A Url.',
    ], $this->getMockBuilder('Apruve\Client')
          ->setConstructorArgs(['a key', Apruve\Environment::DEV()])
          ->getMock()
  );
  }

  protected function tearDown() {
    
  }

  public function testPropertiesAreDefined()
  {
    $item_vars = get_class_vars(get_class($this->item));

    $this->assertEquals(array_keys($item_vars),array(
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
    ));
    $this->assertEquals(10, count($item_vars));
  }

  public function testToJsonString()
  {
    $this->assertJsonStringEqualsJsonString(
      '{
          "title": "A title",
          "amount_cents": 3400,
          "quantity": 1,
          "price_ea_cents": 3400,
          "merchant_notes": "some notes.",
          "description": "a description.",
          "variant_info": "some variation.",
          "sku": "a sku",
          "vendor": "ACME",
          "view_product_url": "A Url."
       }', $this->item->toJson()
    );
  }


}

