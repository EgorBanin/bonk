<?php

namespace http;

/**
 * Хак: переопределяем стандартные функции
 */
$sended_headers = [];

function headers_sent() {
	return false;
}

function header($header) {
	global $sended_headers;
	
	$sended_headers[] = $header;
}

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
	
	public function test_send_response() {
		$response = new response(['HelloHeader: hello'], 'hello world');
		$output_stream = fopen('php://temp', 'r+');
		send_response($response, $output_stream);
		rewind($output_stream);
		$body = stream_get_contents($output_stream);
		fclose($output_stream);
		$this->assertEquals($response->body, $body);
		global $sended_headers;
		foreach ($response->headers as $header) {
			$this->assertContains($header, $sended_headers);
		}
	}
}