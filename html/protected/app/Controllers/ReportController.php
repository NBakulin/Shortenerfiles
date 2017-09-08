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

        $index->get('/shorten_urls/{id}/minutes', function ($id) use ($app){
            $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];
            $redirectTable= $app['models']($app)->load('RedirectCounter');
            $redirectTable ->load();
            $rowToWrite = $redirectTable->groupByMinutes($from_date, $to_date, $id);
            return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/hours', function ($id) use ($app){
            $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];
            $redirectTable= $app['models']($app)->load('RedirectCounter');
            $redirectTable ->load();
            $rowToWrite = $redirectTable->groupByHours($from_date, $to_date, $id);
            return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);

        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/days', function ($id) use ($app){
            $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];
            $redirectTable= $app['models']($app)->load('RedirectCounter');
            $redirectTable ->load();
            $rowToWrite = $redirectTable->groupByDays($from_date, $to_date, $id);
            return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);

        })
            ->method('GET');

        return $index;
    }
}