<?php

namespace App;

class RouterTest extends \PHPUnit_Framework_TestCase {
    
	public function testRoute() {
		$router = new Router([
			'/foo/bar' => 'fooBar.php',
			'/foo/$id' => 'fooBar.php',
			
			'/rest/$resource' => '$resource/$method.php',
			'/rest/$resource/$id' => '$resource/$method.php',
			
			function($path, array &$params = []) {
				$matches = [];
				if (preg_match('~^/user_([0-9]+)$~', $path, $matches) === 1) {
					$params['id'] = $matches[1];
					
					return 'users/view.php';
				} else {
					return false;
				}
			},
			
			// типичный набор маршрутов
			'/' => 'index.php',
			'/$module' => '$module/index.php',
			'/$module/$action' => '$module/$action.php',
			'/$module/$id/$action' => '$module/$action.php',
		]);
		
		$this->assertSame('fooBar.php', $router->route('/foo/bar/'));
		
		$params = [];
		$this->assertSame('fooBar.php', $router->route('foo/123/', $params));
		$this->assertArraySubset(['id' => '123'], $params);
		
		$params = ['method' => 'post'];
		$this->assertSame('products/post.php', $router->route('/rest/products', $params));
		$params = ['method' => 'get'];
		$this->assertSame('products/get.php', $router->route('/rest/products/123', $params));
		$this->assertArraySubset(['id' => '123'], $params);
		
		$params = [];
		$this->assertSame('users/view.php', $router->route('/user_123', $params));
		$this->assertArraySubset(['id' => '123'], $params);
		
		$this->assertSame('index.php', $router->route('/'));
		$this->assertSame('articles/index.php', $router->route('/articles'));
		$this->assertSame('articles/create.php', $router->route('/articles/create'));
		$params = [];
		$this->assertSame('articles/view.php', $router->route('/articles/123/view', $params));
		$this->assertArraySubset(['id' => '123'], $params);
		
		
		$this->assertSame(false, $router->route('/foo/123/bar/baz')); // неизвестный url
	}
    
}

