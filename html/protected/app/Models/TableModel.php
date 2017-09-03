<?php
class ReferenceModel
{
    private $count = 0;
    private $table = Array();
    public function __construct(){}

    /*
* @return array
    */
    public function getHeaders()
    {
        return Array('id', 'User Id', 'Title', 'Initial Ref', 'Shorted Ref', 'Registration Date', 'Followers Count');
    }
    /*
     * @return void
     */
    public  function load()
    {
        $connection = TableModel::getConnection();
        $queryResult = $connection->query("select * from ref");
        if (!$queryResult) {
            throw new Exception('Query result is null');
        }
        for ($count = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $count++) {
            $this->table[$count] = Array(
                "row"=>       $row,
                "isAdded"=>   false,
                "isEnded"=>   false);
            $this->count++;
        }
        mysqli_close($connection);
    }
    /**
     * @return void
     */
    public function save()
    {
        $connection = TableModel::getConnection();
        $queryResult = $connection->query("delete from ref");
        for ($i=0; $i < $this->count(); $i++) {
            $queryResult = $connection->query("insert into ref values 
												(NUll, '".
                $this->table[$i]['row']['userid'].    "', '".
                $this->table[$i]['row']['initialRef']."', '".
                $this->table[$i]['row']['shortedRef']."', '".
                $this->table[$i]['row']['title'].     "', '".
                $this->table[$i]['row']['date'].      "', '".
                $this->table[$i]['row']['count'].	  "')");
        }
    }
    /**
     * @param array $row
     * @return int offset
     */
    public function addRow(array $row)
    {
        if (is_numeric($row[0]) && is_numeric($row[1]) && is_numeric($row[6]) && strtotime($row[5])) {
            $newRow = Array("refid" => $row[0], "userid" => $row[1], "initialRef" => $row[2], "shortedRef" => $row[3], "title" => $row[4], "date" => $row[5], "count" => $row[6]);
            $this->table[$this->count] = Array(
                "row" => $newRow,
                "isAdded" => false,
                "isEnded" => false);
            $this->count++;
        }
        else echo "Неверный формат входных данных, выводим БД без изменений! \n";

    }
    /**
     * @param int $offset
     * @param array $row
     * @return boolean
     */
    public function updateRow($offset, array $row)    {
        if (is_numeric($row[0]) && is_numeric($row[1]) && is_numeric($row[6]) && strtotime($row[5])) {
            if ($offset >= 0 && $offset < $this->count){
                $newRow = Array("refid" => $row[0], "userid" => $row[1], "initialRef" => $row[2], "shortedRef" => $row[3], "title" => $row[4], "date" => $row[5], "count" => $row[6]);
                $this->table[$offset]["row"] = $newRow;
                $this->table[$offset]["isAdded"] = true;
                $this->table[$offset]["isEnded"] = false;
                return TRUE;
            }         else {
                echo "Индекс находится вне границ массива! Выводим БД без изменений... \n";
                return FALSE;
            }
        }
        else echo "Неверный формат входных данных, выводим БД без изменений! \n";
    }
    /**
     * @param int $offset
     * @return array|null
     */
    public function getRow($offset)
    {
        if ($offset >= 0 && $offset < $this->count){
            return $this->table[$offset]["row"];
        }
        else {
            echo "Индекс находится вне границ массива! Выводим БД без изменений... \n";
            return NULL;
        }
    }
    /**
     * @param $offset integer
     * @return boolean
     */
    public function deleteRow($offset)
    {
        if ($offset >= 0 && $offset < $this->count){
            if (is_array($this->table) && array_key_exists($offset, $this->table)) {
                unset($this->table[$offset]);
                $this->table = array_values($this->table);
                $this->count--;
                return true;
            }
        }
        else {
            echo "Индекс находится вне границ массива! Выводим БД без изменений... \n";
        }
    }
    /**
     * @return array
     */
    public function getRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++)
            $tableWithValues[$i] = $this->table[$i]["row"];
        return $tableWithValues;
    }
    /**
     * @return int
     */
    public function countRows()
    {
        return $this->table->count();
    }

    public function count() {
        return $this->count;
    }

    private function getConnection()
    {
        $connection = new mysqli('localhost', 'root', 'Nick1997', 'refDB');
        if ($connection->connect_error) {
            die('Ошибка подключения '. $connection->connect_errno .' - '.$connection->connect_error);
        }
        return $connection;
    }

    public function FindShorteredReference($initialRef) {
        $connection = TableModel::getConnection();
        $queryResult = $connection->query("select shortedRef
										         from ref
										         where initialRef==$initialRef");
        return $queryResult;
        /**
         * @return int
         */
    }
    public function CreateShortenRef($initialRef) {
        $connection = TableModel::getConnection();
        $queryResult = $connection->query("select shortedRef
										         from ref
										         where initialRef==$initialRef");
        return $queryResult;
    }
}
?>