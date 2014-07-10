<?php

require_once dirname(__FILE__) . '/../../src/Apruve/SubscriptionAdjustment.php';
require_once dirname(__FILE__) . '/../../src/Apruve/Client.php';

use Apruve\SubscriptionAdjustment;

class SubscriptionAdjustmentTest extends PHPUnit_Framework_TestCase
{

  protected function setUp()
  {
    Apruve\Client::init('a key', Apruve\Environment::DEV());
    $this->subscription_adjustment = new SubscriptionAdjustment([
      'title' => 'test',
      'amount_cents' => 100,
      'quantity' => 1,
      'price_ea_cents' => 100
    ]);

  }

  public function testPropertiesAreDefined()
  {
    $vars = get_class_vars(get_class($this->subscription_adjustment));

    $this->assertEquals(array_keys($vars),[
      'id',
      'subscription_id',
      'status',
      'title',
      'amount_cents',
      'quantity',
      'price_ea_cents',
      'merchant_notes',
      'description',
      'variant_info',
      'sku',
      'vendor',
      'view_product_url'
    ]);
    $this->assertEquals(13, count($vars));
    
  }

  public function testToJson()
  {
    $this->assertJsonStringEqualsJsonString(
      '{
        "title": "test",
        "amount_cents": 100,
        "quantity": 1,
        "price_ea_cents": 100,
        "merchant_notes": null,
        "description": null,
        "variant_info": null,
        "sku": null,
        "vendor": null,
        "view_product_url": null
       }',
       $this->subscription_adjustment->toJson()
     );
  }

  public function testGet()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['get'])
      ->getMock();
    $client->expects($this->Once())
      ->method('get')
      ->with($this->equalTo('/subscriptions/asdf1234/adjustments/1234asdf'))
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

    $pr = SubscriptionAdjustment::get('asdf1234', '1234asdf', $client);

    $this->assertEquals('asdf1234', $pr->id);
    $this->assertEquals('Apruve\SubscriptionAdjustment', get_class($pr));
  }

  public function testSave()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['post'])
      ->getMock();
    $client->expects($this->Once())
      ->method('post')
      ->with($this->equalTo('/subscriptions/asdf1234/adjustments/'),
        $this->anything())
      ->will($this->returnValue([
        201,
        [
          'id' => 'asdf1234',
          'subscription_id' => 'asdf1234',
          'amount_cents' => 100,
        ],
        ''])
      );

    $subscription_adjustment = new SubscriptionAdjustment([
      "subscription_id" => 'asdf1234',
      "title" => 'test',
      "amount_cents" => 100,
      "quantity" => 1,
      "price_ea_cents" => 100], $client);
    $sr = $subscription_adjustment->save();

    $this->assertEquals('asdf1234', $sr->id);
    $this->assertEquals('Apruve\SubscriptionAdjustment', get_class($sr));
  }

  public function testDelete()
  {
    $client = $this->getMockBuilder('Apruve\Client')
      ->setMethods(['delete'])
      ->getMock();
    $client->expects($this->Once())
      ->method('delete')
      ->with($this->equalTo('/subscriptions/asdf1234/adjustments/asdf1234'))
      ->will($this->returnValue([
          200,
          [
            'id' => 'asdf1234',
            'subscription_id' => 'asdf1234',
            'api_url' => 'www.foo.com'
          ],
          ''])
      );

    $sr = SubscriptionAdjustment::delete('asdf1234', 'asdf1234', $client);
    $this->assertEquals('asdf1234', $sr->id);
    $this->assertEquals('asdf1234', $sr->subscription_id);
    $this->assertEquals('www.foo.com', $sr->api_url);

    $this->assertEquals('Apruve\SubscriptionAdjustment', get_class($sr));
  }
      

}

