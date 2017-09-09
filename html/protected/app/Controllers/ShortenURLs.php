<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;

class ShortenURLs implements ControllerProviderInterface {

    public function connect(Application $app) {
        $index = new ControllerCollection(new Route());


        $index->post('/', function () use ($app){
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                echo 'Для добавления ссылки нужно авторизоваться!';
                exit;
            } else {
                $userTable= $app['models']($app)->load('UserModele');
                $userTable->load();
                $user = $userTable->getUserByBasicAuth();
                $content = file_get_contents('php://input');
                $newRow = json_decode($content, true);
                if (!isset($newRow["initialRef"]))
                    return json_encode(-1);
                $row = [$newRow["initialRef"], $newRow["title"]];
                $refTable= $app['models']($app)->load('ReferenceModel');
                $refTable->load();
                $translator = $app['models']($app)->load('ShortenerModel');
                $row =  ["is generating", $user["userid"], $newRow["initialRef"], $translator->translate($refTable->getLastID()), $newRow["title"],  date_create('now')->format('Y\-m\-d\ h:i:s'), '0'];
                $refTable->addRow($row);
                $refTable->save();
                $rowToWrite = $refTable->getRows();
                return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
            }

        })
            ->method('POST');


        $index->get('/', function () use ($app){
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                echo 'Для просмотра списка ссылок нужно авторизоваться!';
                exit;
            } else {
                $refTable = $app['models']($app)->load('ReferenceModel');
                $refTable->load();
                $userTable= $app['models']($app)->load('UserModel');
                $userTable->load();
                $user = $userTable->getUserByBasicAuth();
                //$refTable->showUsersReferences($user);
                $rowToWrite =$refTable->showUsersReferences($user);
                return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
            }

        })
            ->method('GET');

        $index->get('/{id}', function ($id) use ($app){
            if (!is_numeric($id))
                echo "Введен не целочисленный индекс, а Бог пойми что!";
            else {
                $refTable= $app['models']($app)->load('ReferenceModel');
                $refTable->load();
                $rowToWrite = $refTable->getRow($id);
                return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
            }
        })
            ->method('GET');

        $index->delete('/{id}', function ($id) use ($app){
            if (!is_numeric($id))
                echo "Введен не целочисленный индекс, а Бог пойми что!";
            else {
                $refTable= $app['models']($app)->load('ReferenceModel');
                $refTable->load();
                echo json_encode($refTable->getRows(), JSON_PRETTY_PRINT);
                $refTable->deleteRow($id);
                echo json_encode($refTable->getRows(), JSON_PRETTY_PRINT);
                $refTable->save();
                echo json_encode($refTable->getRows(), JSON_PRETTY_PRINT);
                $rowToWrite = $refTable->getRows();
                return $app['views']($app)->render('Home', 'getRow', ['rowToWrite'=>$rowToWrite]);
            }
        })
            ->method('DELETE');


        return $index;
    }
}
