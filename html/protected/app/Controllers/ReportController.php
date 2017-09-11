<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;
use Repository\RedirectRepository;
use Repository\UserRepository;
use Repository\ReferenceRepository;
use Models\UserService;
use Models\ReferenceService;

class ReportController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $index = new ControllerCollection(new Route());

        $index->get('/shorten_urls/{id}/min', function ($id) use ($app){
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->GetUserByBasicAuth();
            if ($user) {
                $reportToShow = $referenceTable->GetRow($id, $user);
                if ($reportToShow !== null) {
                    $from_date = $_GET['from_date']; $to_date = $_GET['to_date'];
                    $redirectRepository = new RedirectRepository();
                    $reportToShow = $redirectRepository->GroupByMinutes($from_date, $to_date, $id);
                    return $app['views']($app)->render('Reports', 'GiveReport', ['reportToShow'=>$reportToShow]);
                }
                else{
                    echo "У вас нет такой ссылки!";
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                    exit;
                }
            } else {
                echo "Вы не авторизованы! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/hours', function ($id) use ($app){
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->GetUserByBasicAuth();
            if ($user) {
                $reportToShow = $referenceTable->GetRow($id, $user);
                if ($reportToShow !== null) {
                    $from_date = $_GET['from_date'];
                    $to_date = $_GET['to_date'];
                    $redirectRepository = new RedirectRepository();
                    $reportToShow = $redirectRepository->GroupByHours($from_date, $to_date, $id);
                    return $app['views']($app)->render('Reports', 'GiveReport', ['reportToShow'=>$reportToShow]);
                }
                else{
                    echo "У вас нет такой ссылки!";
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 404 );
                    exit;
                }
            } else {
                echo "Вы не авторизованы! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/days', function ($id) use ($app){
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->getUserByBasicAuth();
            if ($user) {
                $reportToShow = $referenceTable->GetRow($id, $user);
                if ($reportToShow !== null) {
                    $from_date = $_GET['from_date'];
                    $to_date = $_GET['to_date'];
                    $redirectRepository = new RedirectRepository();
                    $reportToShow = $redirectRepository->GroupByDays($from_date, $to_date, $id);
                    return $app['views']($app)->render('Reports', 'GiveReport', ['reportToShow'=>$reportToShow]);
                }
                else{
                    echo "У вас нет такой ссылки!";
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 404 );
                    exit;
                }
            } else {
                echo "Вы не авторизованы! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');

        $index->get('/shorten_urls/{id}/referers', function ($id) use ($app){
            $refRepository = new ReferenceRepository();
            $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
            $userRepository = new UserRepository();
            $userTable = new UserService($userRepository->GetArray(), $userRepository->Count());
            $user = $userTable->GetUserByBasicAuth();
            if ($user) {
                    $redirectRepository = new RedirectRepository();
                    $reportToShow = $redirectRepository->GetTop20($id);
                return $app['views']($app)->render('Reports', 'GiveReport', ['reportToShow'=>$reportToShow]);
            } else {
                echo "Вы не авторизованы! ";
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error403', true, 403 );
                exit;
            }
        })
            ->method('GET');

        return $index;
    }
}