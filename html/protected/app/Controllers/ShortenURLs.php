<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;
use Repository\UserRepository;
use Repository\ReferenceRepository;
use Models\UserService;
use Models\ShortenerModel;
use Models\ReferenceService;
class ShortenURLs implements ControllerProviderInterface {

    public function connect(Application $app) {
        $index = new ControllerCollection(new Route());


        $index->post('/shorten_urls', function () use ($app){
                $newRow = json_decode(file_get_contents('php://input'), true);
                if ($newRow["initialRef"]=='')
                return json_encode(-1);
                $refRepository = new ReferenceRepository();
                $userRepository= new UserRepository();
                $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
                $user = $userTable->GetUserByBasicAuth();
                if ($user ) {
                    if (!$refRepository->CheckExistance($newRow["initialRef"], $user)) {
                        $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->count(), $refRepository->GetLastID());
                        $translator = new ShortenerModel();
                        $row = ["is generating", $user["userid"], $newRow["initialRef"], $translator->Translate($referenceTable->GetLastID()), $newRow["title"], date_create('now')->format('Y\-m\-d\ h:i:s'), '0'];
                        $referenceTable->AddRow($row);
                        $refRepository->Save($referenceTable->GetArray(), $referenceTable->Count());
                        echo "Ссылка создана. ";
                        exit;
                    }
                    else {
                        echo "Такая ссылка уже существует! ";
                        header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                        exit;
                    }
                }
                else{
                    echo "Для добавления ссылки нужно авторизоваться! ";
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                    exit;
                }
        })
            ->method('POST');


        $index->get('/shorten_urls', function () use ($app) {
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->getUserByBasicAuth();
            if ($user) {

                $referencesToShow = $referenceTable->getRows();
                $referencesToShow =$referenceTable->showUsersReferences($user);
                return $app['views']($app)->render('ReferencesWorking', 'ShowReferences', ['referencesToShow' => $referencesToShow]);
            } else {
                echo "Для поиска ваших ссылок нужно авторизоваться! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');

        $index->get('/shorten_urls/{id}', function ($id) use ($app){
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->GetUserByBasicAuth();
            if ($user) {
                $referencesToShow = $referenceTable->GetRow($id, $user);
                return $app['views']($app)->render('ReferencesWorking', 'ShowReferences', ['referencesToShow' => $referencesToShow]);
            } else {
                echo "Для поиска вашей ссылки нужно авторизоваться! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');

        $index->delete('/shorten_urls/{id}', function ($id) use ($app){
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->GetUserByBasicAuth();
            if ($user) {
                if ($referenceTable->DeleteRow($id, $user)) {
                    $refRepository->Save($referenceTable->GetArray(), $referenceTable->Count());
                    echo "Ссылка с id=$id найдена и удалена. \n";
                    exit;
                }
                else {
                   echo "Ссылки с id=$id не найдено!";
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 404 );
                    exit;
                }
            } else {
                echo "Для удаления ссылки нужно авторизоваться! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('DELETE');


        return $index;
    }
}
