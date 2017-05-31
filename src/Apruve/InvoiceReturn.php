<?php

namespace Apruve;

require_once 'ApruveObject.php';

class InvoiceReturn extends ApruveObject {

	protected static $REFUND_PATH = '/invoices/%s/invoice_returns/';
	protected static $hash_order = [
		'invoice_id',
		'amount_cents',
		'currency',
		'reason',
	];
	protected static $json_fields = [
		'invoice_id',
		'amount_cents',
		'currency',
		'reason',
	];
	var $invoice_id;
	var $amount_cents;
	var $currency;
	var $reason;

	public function __construct( $values = [], $client = null ) {
		$this->currency = "USD";
		$this->reason   = "OTHER";
		parent::__construct( $values, $client );
	}

	public static function get( $invoice_id, $invoice_return_id, $client = null ) {
		if ( $client == null ) {
			$client = new Client();
		}
		$response = $client->get( sprintf( self::$REFUND_PATH, $invoice_id ) . $invoice_return_id );

		if ( $response[0] == 200 ) {
			return new self( $response[1], $client );
		} else {
			return $response[2];
		}
	}

	public function save() {
		$response = $this->client->post(
			sprintf(
				self::$REFUND_PATH, $this->invoice_id ), $this->toJson() );
		if ( $response[0] == 201 ) {
			return new self( $response[1], $this->client );
		} else {
			return $response[2];
		}
	}

}
