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


        $index->get('/{id}/days?from_date={datefirst}&to_date={datesecond}/', function ($datefirst, $datesecond) use ($app){

        })
            ->method('GET');

        $index->get('/{id}/hours?from_date={datefirst}&to_date={datesecond}/', function ($datefirst, $datesecond) use ($app){

        })
            ->method('GET');

        $index->get('/{id}/minutes?from_date={datefirst}&to_date={datesecond}/', function ($datefirst, $datesecond) use ($app){

        })
            ->method('GET');

        return $index;
    }
}