<?php

require_once 'io.php';

class IoTest extends PHPUnit_Framework_TestCase {

	public function testOpt() {
		$this->assertSame([
			'foo' => true,
			'bar' => 'xxx',
			'baz' => '',
			'quux' => 'yy yy'
		], \wub\io_opt([
			'foo',
			'bar=xxx',
			'-baz=',
			'--quux=yy yy',
		]));
	}

}