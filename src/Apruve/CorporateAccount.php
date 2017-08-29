<?php
// Invoice
namespace Apruve;

require_once 'ApruveObject.php';

class CorporateAccount extends ApruveObject {

	protected static $ACCOUNTS_PATH = '/merchants/%s/corporate_accounts';
	protected static $hash_order = [];
	protected static $json_fields = [
		'id',
		'customer_uuid',
		'merchant_uuid',
		'type',
		'payment_term_strategy_name',
		'name',
		'authorized_buyers',
	];

	var $id;
	var $customer_uuid;
	var $merchant_uuid;
	var $type;
	var $payment_term_strategy_name;
	var $name;
	var $authorized_buyers = [];

	public static function get( $merchant_key, $email, $client = null ) {
		if ( $client == null ) {
			$client = new Client();
		}
		$response = $client->get( sprintf( self::$ACCOUNTS_PATH, $merchant_key ) . '?email=' . $email );

		if ( $response[0] == 200 ) {
			return new self( array_pop( $response[1] ), $client );
		} else {
			return $response[2];
		}
	}
}
