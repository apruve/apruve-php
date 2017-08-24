<?php

namespace Apruve;

abstract class ApruveObject {

	protected $client;

	public function __construct( $values = [], $client = null ) {
		if ( $client == null ) {
			$this->client = new Client();
		} else {
			$this->client = $client;
		}
		if ( is_array( $values ) || is_object( $values ) ) {
			foreach ( $values as $name => $value ) {
				if ( is_array( $value ) ) {
					$this->$name = [];
					$this->$name = $value;
				} else {
					$this->$name = $value;
				}
			}
		}
	}

	public function toHashString() {
		$ret          = '';
		$called_class = get_called_class();
		foreach ( $called_class::$hash_order as $key ) {
			if ( is_array( $this->$key ) ) {
				foreach ( $this->$key as $item ) {
					$ret .= $item->toHashString();
				}
			} else {
				$ret .= $this->$key;
			}
		}

		return $ret;
	}

	public function toJson() {
		return json_encode( $this->toJsonArray() );
	}

	public function toJsonArray() {
		$jsonArr      = [];
		$called_class = get_called_class();
		foreach ( $called_class::$json_fields as $key ) {
			if ( gettype( $this->$key ) == "array" ) {
				$jsonArr[ $key ] = [];
				foreach ( $this->$key as $item ) {
					if ( is_array( $item ) || is_string( $item ) ) {
						array_push( $jsonArr[ $key ], $item );
					} else {
						array_push( $jsonArr[ $key ], $item->toJsonArray() );
					}
				}
			} else {
				$jsonArr[ $key ] = $this->$key;
			}
		}

		return $jsonArr;
	}
}
