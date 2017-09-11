<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;
use Repository\UserRepository;
use Models\UserService;
class Home implements ControllerProviderInterface {
	
	public function connect(Application $app) {
		    $index = new ControllerCollection(new Route());


		    $index->post('/users', function () use ($app){
                $userRepository= new UserRepository();
                $requestContent = json_decode( file_get_contents('php://input'), true);
                if ( $requestContent["name"]==''||$requestContent["email"]==''||$requestContent["login"]==''||$requestContent["password"]==''){
                    echo "Пользователь должен быть представлен в json формате (email, login, name, password).";
                    exit;
                }
                else {
                    $userTable = new UserService($userRepository->GetArray(), $userRepository->count());
                    if ($userTable->CheckExistance($requestContent["login"], $requestContent["email"]) === false) {
                        $userTable->CreateUser($requestContent);
                        $userRepository->Save($userTable->GetArray(), $userTable->Count());
                        echo "Пользователь успешно создан!" . "\n" . "Ваш логин - " . $requestContent["login"] . "\n" . "Ваш пароль - " . $requestContent["password"] . "\n" . "Запишите их!";
                        exit;
                    } else {
                        echo "Пользователь с таким логином и/или адресом почты уже существует!";
                        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/Error403', true, 403);
                        exit;
                    }
                }

             })
            ->method('POST');


        $index->get('/users/me', function () use ($app){
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->GetUserByBasicAuth();
            if ($user) {
                $userRepository = new UserRepository();
                $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
                $user = $userTable->GetUserByBasicAuth();
                return $app['views']($app)->render('Home', 'ShowUserAuth', ['user'=>$user]);
            }
            else {
                echo "Вы не авторизованы!! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');


		return $index;
	}
}
