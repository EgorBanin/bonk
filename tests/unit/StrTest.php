<?php

require_once 'str.php';

class StrTest extends PHPUnit_Framework_TestCase {
	
	public function testTemplate() {
		$this->assertSame(
			'The quick brown fox jumps over the lazy dog',
			\wub\str_template('The {foo} jumps over the {bar}', [
				'foo' => 'quick brown fox',
				'bar' => 'lazy dog',
			]),
			'Переменные подставились верно'
		);
	}
	
}

