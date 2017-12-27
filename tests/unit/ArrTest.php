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
	
	public function testIndex() {
		$arr = [['id' => 9, 'name' => 'foo'], ['id' => 10, 'name' => 'bar']];
		$this->assertSame([
			9 => ['id' => 9, 'name' => 'foo'],
			10 => ['id' => 10, 'name' => 'bar'],
		], \wub\arr_index($arr, 'id'));
		
		$arr = [
			['id' => 12, 'name' => 'foo'],
			['id' => 10, 'name' => 'bar'],
			['name' => 'baz'],
		];
		$this->assertSame([
			12 => ['id' => 12, 'name' => 'foo'],
			10 => ['id' => 10, 'name' => 'bar'],
		], \wub\arr_index($arr, 'id'));
		
		$arr = [['id' => 1, 'name' => 'foo'], ['id' => 1, 'name' => 'bar']];
		$this->assertSame([
			1 => ['id' => 1, 'name' => 'bar'],
		], \wub\arr_index($arr, 'id'));
		
		$this->assertSame([], \wub\arr_index([], 'x'));
	}

	public function testComb() {
		$dataProvider = [
			[
				[['a', 'b'], [1, 2, 3]],
				[
					['a', 1],
					['a', 2],
					['a', 3],
					['b', 1],
					['b', 2],
					['b', 3],
				]
			],
			[
				[['a', 'b'], [1, 2], ['X', 'Y']],
				[
					['a', 1, 'X'],
					['a', 1, 'Y'],
					['a', 2, 'X'],
					['a', 2, 'Y'],
					['b', 1, 'X'],
					['b', 1, 'Y'],
					['b', 2, 'X'],
					['b', 2, 'Y'],
				]
			],
			[
				[],
				[]
			],
			[
				[[]],
				[[]]
			],
			[
				[['z']],
				[['z']]
			],
			[
				[['z'], []],
				[]
			],
			[
				[['a', 'b'], [1, 2], ['X', 'Y'], [[1],[2]]],
				[
					['a', 1, 'X', [1]],
					['a', 1, 'X', [2]],
					['a', 1, 'Y', [1]],
					['a', 1, 'Y', [2]],
					['a', 2, 'X', [1]],
					['a', 2, 'X', [2]],
					['a', 2, 'Y', [1]],
					['a', 2, 'Y', [2]],
					['b', 1, 'X', [1]],
					['b', 1, 'X', [2]],
					['b', 1, 'Y', [1]],
					['b', 1, 'Y', [2]],
					['b', 2, 'X', [1]],
					['b', 2, 'X', [2]],
					['b', 2, 'Y', [1]],
					['b', 2, 'Y', [2]],
				]
			],
			[
				[[['a']], [[1], [2]]],
				[
					[['a'], [1]],
					[['a'], [2]],
				]
			],
		];

		foreach ($dataProvider as $set) {
			list($args, $expected) = $set;
			$actual = \wub\arr_comb(...$args);
			$this->assertSame($actual, $expected);
		}
	}
	
}
