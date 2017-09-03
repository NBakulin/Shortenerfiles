<?php
namespace Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;

class ModelsServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {
        $app['models.path'] = array();
        $app['models']      = $app->protect(function($app) {
            return new Models($app);
        });
    }
    public function boot(Container $app) {
    }
}
class Models {
    private $app;

    public function __construct(Container $app) {
        $this->app = $app;
    }

    public function load($modelName, $data = array()) {
        require_once $this->app['models.path'].$modelName.'.php';

        return new $modelName($data);
    }
}