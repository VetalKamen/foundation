<?php

interface CustomHttpClientAdapterInterface {
	public function get( string $uri, array $options= [] );

	public function post( string $uri, array $options= [] );
}