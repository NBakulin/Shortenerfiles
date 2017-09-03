<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;

class Index implements ControllerProviderInterface {
	
	public function connect(Application $app) {
		$index = new ControllerCollection(new Route());

		$index->get('/', function() use ($app) {
			$label = $app['models']($app)->load('Pages', 'index');

			return $label;
		});

		$index->get('/{name}', function($name) use ($app) {
			$name = $app['models']($app)->load('Pages', 'hello', $name);

			return "Hello{$name}";
		});

		return $index;
	}
}