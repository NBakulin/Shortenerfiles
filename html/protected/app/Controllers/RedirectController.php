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
                $refTable->load();
                $initialRef = $refTable ->FindInitialReference($hash);
                if (!$initialRef['initialRef']) {
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 404 );
                }
                else {
                    $refTable->updateRow($initialRef['refid']);
                    $refTable->save();
                    header( 'Location: '.$initialRef['initialRef'], true, 302 );
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
