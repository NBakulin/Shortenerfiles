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
                    $repository->load();
                    $content = file_get_contents('php://input');
                    $newRow = json_decode($content, true);
                    if (!isset($newRow["email"],$newRow["login"],$newRow["name"],$newRow["password"]))
                        return json_encode(-1);
                    $row = [null,$newRow["email"],$newRow["login"],$newRow["name"],$newRow["password"]];
                    $userTable = new UserModel($repository->GetArray(), $repository->count());
                    if ($userTable->CheckExistance($row))
                    {
                        $userTable->createUser($row);
                        $repository->save($userTable->getArray(), $userTable->count());
                        $rowToWrite = $userTable ->getRows();
                        return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
                    }
                    else{
                        echo "Данный пользователь уже существует!";
                    }

             })
            ->method('POST');


        $index->get('/me', function () use ($app){
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                echo 'Введены неправильные логин и/или пароль.';
                exit;
            } else {
                $repository = new UserRepository();
                $repository->load();
                $userTable = new UserModel($repository->GetArray(), $repository->count());
                $user = $userTable->getUserByBasicAuth();
                return $app['views']($app)->render('Home', 'ShowUserAuth', ['user'=>$user]);
            }
        })
            ->method('GET');


		return $index;
	}
}
