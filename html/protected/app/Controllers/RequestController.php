<?php
/**
 * Created by PhpStorm.
 * User: bakul
 * Date: 23.08.2017
 * Time: 21:48
 */

use mysqli;
use \Symfony\Component\HttpFoundation as Http;
use  \Silex\Application as Application;

class RequestController{
    public function getConnection()
    {
        $connection = new mysqli('localhost', 'root', 'Nick1997', 'refDB');
        if ($connection->connect_error) {
            die('Ошибка подключения '. $connection->connect_errno .' - '.$connection->connect_error);
        }
        return $connection;
    }

    function Route( $app){
        $app->get('/', function () {
            $table = new TableModel();
            $table -> load();
            $response = new Http\Response();
            $response->headers->set('GET/', '200');
            $response->setStatusCode(200, 'GET/ has gone well');
            $response->setContent(json_encode($table->getRows(), JSON_PRETTY_PRINT));
            return $response;
        })
            ->method('GET');


        $app->post('/', function () use ($app){
            $response = new Http\Response();
            $content = file_get_contents('php://input');
            $newRow = json_decode($content, true);
            if (!isset($newRow["refid"]))
                return json_encode(-1);
            $row = [$newRow["refid"],$newRow["userid"],$newRow["initialRef"],$newRow["shortedRef"],$newRow["title"],$newRow["date"], $newRow["count"]];
            $table = new TableModel();
            $table -> load();
            $table->addRow($row);
            $table->save();
            $response->setContent(json_encode($table->getRows(), JSON_PRETTY_PRINT));
            return $response;
        })
            ->method('POST');


        $app->get('/{id}', function ($id)  use ($app) {
            if (!is_numeric($id))
                echo "Введен не целочисленный индекс, а Бог пойми что!";
                else {
                    $response = new Http\Response();
                    $table = new TableModel();
                    $table->load();
                    $rowToWrite = $table->getRow($id);
                    $response->setContent(json_encode($rowToWrite, JSON_PRETTY_PRINT));
                }
            return $response;
        })
            ->method('GET');


        $app->put('/{id}', function ($id)  use ($app)  {
            if (!is_numeric($id))
                echo "Введен не целочисленный индекс, а Бог пойми что!";
            else {
                $response = new Http\Response();
                $content = file_get_contents('php://input');
                $newRow = json_decode($content, true);
                if (!isset($newRow["refid"]))
                    return json_encode(-1);
                $row = [$newRow["refid"], $newRow["userid"], $newRow["initialRef"], $newRow["shortedRef"], $newRow["title"], $newRow["date"], $newRow["count"]];
                $table = new TableModel();
                $table->load();
                $table->updateRow($id, $row);
                $table->save();
                $response->setContent(json_encode($table->getRows(), JSON_PRETTY_PRINT));
            }
            return $response;
        })
            ->method('PUT');


        $app->delete('/{id}', function ($id)  use ($app)  {
            if (!is_numeric($id))
                echo "Введен не целочисленный индекс, а Бог пойми что!";
            else {
                $response = new Http\Response();
                $table = new TableModel();
                $table->load();
                $table->deleteRow($id);
                $table->save();
                $response->setContent(json_encode($table->getRows(), JSON_PRETTY_PRINT));
            }
            return $response;
        })
            ->method('DELETE');

    }
}
?>