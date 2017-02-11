<?php

require_once 'obj.php';

class Foo {
	
	private $foo;
	
	protected $bar;
	
	public $baz;
	
	function __construct($foo, $bar, $baz) {
		\wub\obj_init($this, get_defined_vars());
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
		$foo = new Foo($options['foo'], $options['bar'], $options['baz']);
		$this->assertSame($options, $foo->getProperies(), 'Свойства установились верно');
		
		$bar = new Bar('hi');
		$this->assertSame('hi', $bar->getBar(), 'Приватное свойство установилось верно');
		\wub\obj_init($bar, ['bar' => 'hack!']);
		$this->assertSame('hack!', $bar->getBar(), 'Приватное свойство изменилось');
	}
	
}
