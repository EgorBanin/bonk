<?php

require_once 'obj.php';

class Foo {
	
	private $foo;
	
	protected $bar;
	
	public $baz;
	
	function __construct($options) {
		\wub\obj_init($this, $options);
	}
	
	public function getProperies() {
		return get_object_vars($this);
	}
	
}

class Bar {
	
	private $bar;
	
	public function __construct($bar) {
		$this->bar = $bar;
	}
	
	public function getBar() {
		return $this->bar;
	}
}

class ObjTest extends PHPUnit_Framework_TestCase {
	
	public function testInit() {
		$options = [
			'foo' => 123,
			'bar' => 'hi',
			'baz' => 'bye',
		];
		$foo = new Foo($options);
		$this->assertSame($options, $foo->getProperies(), 'Свойства установились верно');
		
		$bar = new Bar('hi');
		$this->assertSame('hi', $bar->getBar(), 'Приватное свойство установилось верно');
		\wub\obj_init($bar, ['bar' => 'hack!']);
		$this->assertSame('hack!', $bar->getBar(), 'Приватное свойство изменилось');
	}
	
}
