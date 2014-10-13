<?php

namespace http;

require_once 'core/http/module.php';

class Test extends \PHPUnit_Framework_TestCase {
	
	public function test_request_from_env() {
		$input_stream = fopen('php://temp', 'r+');
		fwrite($input_stream, 'foo bar');
		rewind($input_stream);
		$this->assertEquals(
			new request('POST', '/some/uri', 'foo bar'),
			request_from_env([
				'REQUEST_METHOD' => 'POST',
				'REQUEST_URI' => '/some/uri'
			], $input_stream)
		);
		
		fclose($input_stream);
		$this->assertEquals(
			new request('UNKNOWN', '/', ''),
			request_from_env([], $input_stream)
		);
	}
	
}