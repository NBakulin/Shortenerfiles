<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;

class RedirectController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $index = new ControllerCollection(new Route());


        $index->get('/{hash}', function ($hash) use ($app){
            try
            {
                $refTable= $app['models']($app)->load('ReferenceModel');
                $initialRef = $refTable ->FindInitialReference($hash);
                if (!$initialRef) {
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 301 );
                }
                else {
                    header( 'Location: '.$initialRef, true, 301 );
                }
                exit;
            }
            catch (Exception $e)
            {
                $exceptionText = 'Ошибка перенаправления. '.$e-getMessage();
            }
            echo self::render(__METHOD__,'Перенаправление',['exceptionText'=>$exceptionText]);
        })
            ->method('GET');

        return $index;
    }
}
