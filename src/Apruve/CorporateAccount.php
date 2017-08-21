<?php
// Invoice
namespace Apruve;

require_once 'ApruveObject.php';

class CorporateAccount extends ApruveObject {

	protected static $ACCOUNTS_PATH = '/merchants/%s/corporate_account';
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

	public function __construct( $corporate_account = [], $client = null ) {
		if ( array_key_exists( 'authorized_buyers', $corporate_account ) ) {
			foreach ( $corporate_account['authorized_buyers'] as $buyer ) {
				if ( is_array( $buyer ) ) {
					$corporate_account['authorized_buyers'] += $buyer;
				}
			}
		}

		parent::__construct( $corporate_account, $client );
	}

	public static function get( $merchant_key, $email, $client = null ) {
		if ( $client == null ) {
			$client = new Client();
		}
		$email_payload = sprintf('{"email":"%s"}', $email);
		$response = $client->get( sprintf( self::$ACCOUNTS_PATH, $merchant_key ), $email_payload );

		if ( $response[0] == 200 ) {
			return new self( $response[1], $client );
		} else {
			return $response[2];
		}
	}
}
