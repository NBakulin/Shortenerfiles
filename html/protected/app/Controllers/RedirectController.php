<?php

namespace Controllers;

use Silex\Application;
use Silex\Route;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Request;
use Repository\RedirectRepository;
use Models\RedirectCounterModel;
use Repository\ReferenceRepository;
use Models\ReferenceService;
class RedirectController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $index = new ControllerCollection(new Route());


        $index->get('/{hash}', function ($hash) use ($app){
                $refRepository = new ReferenceRepository();
                $referenceTable = new ReferenceService($refRepository->GetArray(), $refRepository->Count(), $refRepository->GetLastID());
                $initialRef = $referenceTable ->FindInitialReference($hash);
                if (!$initialRef['initialRef']) {
                    echo "Такой ссылки не найдено!";
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 404 );
                }
                else {
                    $referenceTable->UpdateRow($initialRef['refid']);
                    $refRepository->Save($referenceTable->GetArray(), $referenceTable->Count());
                    $redirectRepository = new RedirectRepository();
                    $redirectTable = new RedirectCounterModel($redirectRepository->GetArray(), $redirectRepository->Count());
                    $leftReference =$_SERVER['HTTP_REFERER'];
                    $redirectTable ->CreateRedirectDate($initialRef['refid'], $leftReference);
                    $redirectRepository->Save($redirectTable->GetArray(), $redirectTable->Count());
                    header( 'Location: '.$initialRef['initialRef'], true, 302 );
                }
                exit;
        })
            ->method('GET');

        return $index;
    }
}
