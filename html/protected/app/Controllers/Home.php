<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;

class Home implements ControllerProviderInterface {
	
	public function connect(Application $app) {
		    $index = new ControllerCollection(new Route());


		    $index->post('/', function () use ($app){
                    $userTable= $app['models']($app)->load('UserService');
                    $content = file_get_contents('php://input');
                    $newRow = json_decode($content, true);
                    if (!isset($newRow["userid"]))
                        return json_encode(-1);
                    $row = [$newRow["userid"],$newRow["email"],$newRow["login"],$newRow["name"],$newRow["password"]];
                    $userTable->load();
                    /*CHECK EXISTANCE*/
                    $userTable->createUser($row);
                    $userTable->save();
               /* echo json_encode( $userTable ->getRows(), JSON_PRETTY_PRINT);
echo "1";*/
               /* echo json_encode( $userTable ->getRows(), JSON_PRETTY_PRINT);*/
                    $rowToWrite = $userTable ->getRows();
                    return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
             })
            ->method('POST');


        $index->get('/me/', function () use ($app){
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                echo 'Текст, отправляемый в том случае,
                если пользователь нажал кнопку Cancel';
               /* return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);*/
                exit;
            } else {
                $userTable= $app['models']($app)->load('UserService');
                $userTable->load();
                $user = $userTable->getUserByBasicAuth();
                return $app['views']($app)->render('Home', 'ShowUserAuth', ['user'=>$user]);
            }
        })
            ->method('GET');


		return $index;
	}
}
