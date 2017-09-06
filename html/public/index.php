<?php

ini_set('display_errors', 'On');

require __DIR__.'/../protected/vendor/autoload.php';

$app = new Silex\Application();

        $app->register(new Providers\ModelsServiceProvider(), array(
            'models.path' => __DIR__.'/../protected/app/Models/'
        ));
        $app->register(new Providers\ViewsServiceProvider(), array(
            'views.path' => __DIR__.'/../protected/app/Views/'
        ));

        $app->mount('/api/v1/users/me/shorten_urls/', new Controllers\ShortenURLs());
        $app->mount('/api/v1/shorten_urls/', new Controllers\RedirectController());
        $app->mount('/api/v1/users/', new Controllers\Home());
        $app->mount('/api/v1/users/me/', new Controllers\ReportController());

    $app->run();



