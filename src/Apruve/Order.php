<?php
// Order
namespace Apruve;

require_once 'ApruveObject.php';

class Order extends ApruveObject {
	protected static $ORDERS_PATH = '/orders';
	protected static $hash_order = [
		'merchant_id',
		'merchant_order_id',
		'amount_cents',
		'currency',
		'tax_cents',
		'shipping_cents',
		'expire_at',
		'invoice_on_create'
	];
	protected static $json_fields = [
		'id',
		'merchant_id',
		'merchant_order_id',
		'amount_cents',
		'currency',
		'tax_cents',
		'shipping_cents',
		'expire_at',
		'po_number',
		'accepts_payment_terms',
		'finalize_on_create',
		'invoice_on_create',
		'payment_term',
		'order_items',
		'shopper_id',
		'customer_id'
	];
	private static $UPDATE_PATH = '/orders/%s';
	private static $CANCEL_PATH = '/orders/%s/cancel';
	private static $INVOICES_PATH = '/orders/%s/invoices';
	var $id;
	var $merchant_id;
	var $username;
	var $status;
	var $merchant_order_id;
	var $amount_cents;
	var $tax_cents;
	var $shipping_cents;
	var $currency;
	var $expire_at;
	var $po_number;
	var $accepts_payment_terms;
	var $finalize_on_create;
	var $invoice_on_create = 'false';
	var $order_items = [];
	var $payment_term = [];
	var $api_url;
	var $view_url;
	var $created_at;
	var $updated_at;

	public function __construct( $order = [], $client = null ) {
		if ( array_key_exists( 'order_items', $order ) ) {
			foreach ( $order['order_items'] as $key => $order_item ) {
				if ( is_array( $order_item ) ) {
					$order['order_items'][ $key ] = new OrderItem( $order_item );
				}
			}
		}

		parent::__construct( $order, $client );
	}

	public static function get( $order_id, $client = null ) {
		if ( $client == null ) {
			$client = new Client();
		}
		$response = $client->get( self::$ORDERS_PATH . '/' . $order_id );

		if ( $response[0] == 200 ) {
			$object = new self( $response[1], $client );

			return $object;
		} else {
			return $response[2];
		}
	}

	public static function getInvoices( $apruveOrderId ) {
		$client   = new Client();
		$response = $client->get( sprintf( __( self::$INVOICES_PATH ), $apruveOrderId ) );

		return $response[1];
	}

	public static function cancel( $apruveOrderId ) {
		$client = new Client();
		echo sprintf( __( self::$CANCEL_PATH, $apruveOrderId ) );
		$response = $client->post( sprintf( __( self::$CANCEL_PATH ), $apruveOrderId ), '' );

		return $response[0] == 200;
	}

	public function setShippingCents( $shipping_cents ) {
		$this->shipping_cents = $shipping_cents;
	}

	public function setTaxCents( $tax_cents ) {
		$this->tax_cents = $tax_cents;
	}

	public function setAmountCents( $amount_cents ) {
		$this->amount_cents = $amount_cents;
	}

	public function setMerchantOrderId( $merchant_order_id ) {
		$this->merchant_order_id = $merchant_order_id;
	}

	public function setExpireAt( $expire_at ) {
		$this->expire_at = $expire_at;
	}

	public function setPoNumber( $po_number) {
		$this->po_number = $po_number;
	}

	public function toSecureHash() {
		$apiKey = $this->client->getApiKey();

		return hash( "sha256", $apiKey . $this->toSecureString() );
	}

	public function toSecureString() {
		$hashString = $this->toHashString();
		foreach ( $this->order_items as $order_item ) {
			$hashString .= $order_item->toHashString();
		}

		return $hashString;
	}

	public function addOrderItem( $order_item ) {
		if ( get_class( $order_item ) == 'Apruve\OrderItem' ) {
			return array_push( $this->order_items, $order_item );
		} else {
			return false;
		}
	}

	public function save() {
		$response = $this->client->post(
			self::$ORDERS_PATH, $this->toJson() );
		if ( $response[0] == 201 ) {
			return new self( $response[1], $this->client );
		} else {
			return $response[2];
		}
	}

	public function update() {
		$response = $this->client->put( sprintf( self::$UPDATE_PATH, $this->id ),
			$this->toJson() );

		return $response[0] == 200;
	}

}
