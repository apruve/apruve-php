<?php

namespace Apruve;

interface HttpRequest {
	public function setOption( $name, $value );

	public function execute();

	public function getInfo( $name );

	public function close();
}

class CurlRequest implements HttpRequest {
	private $ch = null;

	public function __construct( $url ) {
		$this->ch = curl_init( $url );
	}

	public function setOption( $name, $value ) {
		curl_setopt( $this->ch, $name, $value );
	}

	public function execute() {
		return curl_exec( $this->ch );
	}

	public function getInfo( $name ) {
		return curl_getinfo( $this->ch, $name );
	}

	public function error() {
		return curl_error( $this->ch );
	}

	public function close() {
		curl_close( $this->ch );
	}
}
