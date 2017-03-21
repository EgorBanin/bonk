<?php

require_once 'arr.php';

class ArrTest extends PHPUnit_Framework_TestCase {
	
	public function testGet() {
		$arr = ['foo' => 123, 'bar' => '', 0 => 'baz'];
		$this->assertSame(123, \wub\arr_get($arr, 'foo'));
		$this->assertSame('', \wub\arr_get($arr, 'bar'));
		$this->assertSame('baz', \wub\arr_get($arr, 0));
		$this->assertSame(null, \wub\arr_get($arr, 'x'));
		$this->assertSame('default', \wub\arr_get($arr, 'x', 'default'));
	}
	
	public function testTake() {
		$arr = ['foo' => 123, 'bar' => '', 0 => 'baz'];
		$this->assertSame(123, \wub\arr_take($arr, 'foo'));
		$this->assertSame(['bar' => '', 0 => 'baz'], $arr);
	}

	public function testPick() {
		$arr = ['foo' => 123, 'bar' => '', 0 => 'baz'];
		$this->assertSame([
			'foo' => 123,
		], \wub\arr_pick($arr, ['foo']));
		$this->assertSame([
			'foo' => 123,
			0 => 'baz',
		], \wub\arr_pick($arr, ['foo', 0]));
	}

	public function testOmit() {
		$arr = ['foo' => 123, 'bar' => '', 0 => 'baz'];
		$this->assertSame([
			'bar' => '',
			0 => 'baz',
 		], \wub\arr_omit($arr, ['foo']));
		$this->assertSame([
			'foo' => 123,
		], \wub\arr_omit($arr, ['bar', 0]));
	}
	
}
