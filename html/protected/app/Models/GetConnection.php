<?php
use mysqli;
use \Symfony\Component\HttpFoundation as Http;
use  \Silex\Application as Application;

class GetConnection{
    public function getConnection()
    {
        $connection = new mysqli('localhost', 'root', 'Nick1997', 'refDB');
        if ($connection->connect_error) {
            die('Ошибка подключения '. $connection->connect_errno .' - '.$connection->connect_error);
        }
        return $connection;
    }
}
?>