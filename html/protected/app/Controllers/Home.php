<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;
use Repository\UserRepository;
use Models\UserModel;
class Home implements ControllerProviderInterface {
	
	public function connect(Application $app) {
		    $index = new ControllerCollection(new Route());


		    $index->post('', function () use ($app){
                    $repository = new UserRepository();
                    $content = file_get_contents('php://input');
                    $newRow = json_decode($content, true);
                    if (!isset($newRow["email"],$newRow["login"],$newRow["name"],$newRow["password"]))
                        return json_encode(-1);
                    $row = [null,$newRow["email"],$newRow["login"],$newRow["name"],$newRow["password"]];
                    echo json_encode($row, JSON_PRETTY_PRINT);
                    /*CHECK EXISTANCE*/
                    $userTable = new UserModel($repository->load());
                     echo json_encode($userTable, JSON_PRETTY_PRINT);


                    $repository->save($userTable);

               /* echo json_encode( $userTable, JSON_PRETTY_PRINT);
echo "1";*/
               /* echo json_encode( $userTable ->getRows(), JSON_PRETTY_PRINT);*/
                    $rowToWrite = $userTable ->getRows();
                    return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
             })
            ->method('POST');


        $index->get('/me', function () use ($app){
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                echo 'Текст, отправляемый в том случае,
                если пользователь нажал кнопку Cancel';
               /* return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);*/
                exit;
            } else {
                $userTable= $app['models']($app)->load('UserModel');
                $userTable->load();
                $user = $userTable->getUserByBasicAuth();
                return $app['views']($app)->render('Home', 'ShowUserAuth', ['user'=>$user]);
            }
        })
            ->method('GET');


		return $index;
	}
}
