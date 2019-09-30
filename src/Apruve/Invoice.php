<?php
// Invoice
namespace Apruve;

require_once 'ApruveObject.php';

class Invoice extends ApruveObject {

	protected static $INVOICES_PATH = '/orders/%s/invoices/';
	protected static $hash_order = [];
	protected static $json_fields = [
		'amount_cents',
		'currency',
		'merchant_notes',
		'invoice_items',
        'tax_cents'
	];
	var $id;
	var $order_id;
	var $status;
	var $amount_cents;
	var $currency;
	var $merchant_notes;
	var $api_url;
	var $view_url;
	var $created_at;
	var $updated_at;
	var $invoice_items = [];
    var $tax_cents;

	public function __construct( $invoice = [], $client = null ) {
		if ( array_key_exists( 'invoice_items', $invoice ) ) {
			foreach ( $invoice['invoice_items'] as $key => $invoice_item ) {
				if ( is_array( $invoice_item ) ) {
					$invoice['invoice_items'][ $key ] = new InvoiceItem( $invoice_item );
				}
			}
		}

		parent::__construct( $invoice, $client );
	}

	public static function get( $order_id, $invoice_id, $client = null ) {
		if ( $client == null ) {
			$client = new Client();
		}
		$response = $client->get( sprintf( self::$INVOICES_PATH, $order_id ) . $invoice_id );

		if ( $response[0] == 200 ) {
			return new self( $response[1], $client );
		} else {
			return $response[2];
		}
	}

	public function save() {
		$response = $this->client->post(
			sprintf(
				self::$INVOICES_PATH, $this->order_id ), $this->toJson() );
		if ( $response[0] == 201 ) {
			return new self( $response[1], $this->client );
		} else {
			return $response[2];
		}
	}

	public function addInvoiceItem( $invoice_item ) {
		if ( get_class( $invoice_item ) == 'Apruve\InvoiceItem' ) {
			return array_push( $this->invoice_items, $invoice_item );
		} else {
			return false;
		}
	}

}
