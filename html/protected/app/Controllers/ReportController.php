<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;

class ReportController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $index = new ControllerCollection(new Route());

        $index->get('/shorten_urls/{id}/days', function ($id) use ($app){
            $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];
            $redirectTable= $app['models']($app)->load('RedirectCounter');
            $redirectTable ->load();
            $returnDates = $redirectTable->groupByDays($from_date, $to_date);
            echo json_encode($returnDates, true);
        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/hours', function ($id) use ($app){
            $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];

        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/minutes', function ($id) use ($app){
            $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];

        })
            ->method('GET');

        return $index;
    }
}