<?php
namespace Repository;
abstract Class Connector
{
    protected $connection;
    protected function __construct()
    {
        $this->connection = $this->getConnection();
    }

    protected function getConnection()
    {
       $connection = new mysqli('localhost', 'root', 'root', 'refDB');
        if ($connection->connect_error) {
            die('Ошибка подключения ' . $connection->connect_errno . ' - ' . $connection->connect_error);
        }
        return $connection;
    }
}