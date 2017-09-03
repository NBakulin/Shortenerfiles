<?php
namespace Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;

class ViewsServiceProvider implements ServiceProviderInterface {

	public function register(Container $app) {
		$app['views.path'] = array();
		$app['views']      = $app->protect(function($app) {
			return new Views($app);
		});
	}

	public function boot(Container $app) {

	}
}

class Views {
	private $app;

	public function __construct(Container $app) {
		$this->app = $app;	
	}

	public function render($modelName, $modelMethod, $data = array()) {
		
		extract($data);

		ob_start();
		ob_implicit_flush(0);

		require $this->app['views.path'].$modelName.'/'.$modelMethod.'.php';
		
		return ob_get_clean();	
	}
}
