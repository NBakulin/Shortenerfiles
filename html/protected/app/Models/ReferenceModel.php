<?php
require_once('Connector.php');
class ReferenceModel extends Connector
{
    private $lastID = 0;
    private $count = 0;
    private $table = Array();
    /*
* @return array
    */
    public function __construct() {
        parent::__construct();
    }
    public function getLastID()
    {
        return  $this->lastID;
    }
    /*
     * @return void
     */
    public  function load()
    {
        $queryResult = $this->connection->query("select * from ref");
        if (!$queryResult) {
            throw new Exception('Query result is null');
        }
        for ($count = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $count++) {
            $this->table[$count] = Array(
                "row"=>       $row,
                "isAdded"=>   false,
                "isEdited"=>   false,
                "isDeleted"=>   false);
            $this->count++;
        }
        $this->lastID = $this->count();
    }

    public  function save()
    {
        for ($i=0; $i < $this->count(); $i++) {
            if ($this->table[$i]["isAdded"] == true){
                $this->connection->query("insert into ref values 
												(null, '".
                    $this->table[$i]['row']['userid'].    "', '".
                    $this->table[$i]['row']['initialRef']."', '".
                    $this->table[$i]['row']['shortedRef']."', '".
                    $this->table[$i]['row']['title'].     "', '".
                    $this->table[$i]['row']['date'].      "', '".
                    $this->table[$i]['row']['count'].	  "')");
            }
        }
        for ($i=0; $i < $this->count(); $i++) {
            if ($this->table[$i]["isDeleted"] == true){
                $refid = $this->table[$i]['row']['refid'];
                $this->connection->query("delete from ref 
                                                where refid = $refid");
                unset($this->table[$i]);
                $this->count--;
            }
        }
    }

    public function addRow(array $row)
    {
     /*   if (is_numeric($row[0]) && is_numeric($row[1]) && is_numeric($row[6]) && strtotime($row[5]->format('Y\-m\-d\ h:i:s'))) {*/
            $newRow = Array("refid" => $row[0], "userid" => $row[1], "initialRef" => $row[2], "shortedRef" => $row[3], "title" => $row[4], "date" => $row[5], "count" => $row[6]);
            $this->table[$this->count] = Array(
                "row" => $newRow,
                "isAdded" => true,
                "isEdited" => false,
                "isDeleted"=>   false);
            $this->count++;
      /*  }
        else echo "Неверный формат входных данных, выводим БД без изменений! \n";*/

    }

    public function updateRow($offset, array $row)    {
        if (is_numeric($row[0]) && is_numeric($row[1]) && is_numeric($row[6]) && strtotime($row[5])) {
            if ($offset >= 0 && $offset < $this->count){
                $newRow = Array("refid" => $row[0], "userid" => $row[1], "initialRef" => $row[2], "shortedRef" => $row[3], "title" => $row[4], "date" => $row[5], "count" => $row[6]);
                $this->table[$offset]["row"] = $newRow;
                $this->table[$offset]["isAdded"] = false;
                $this->table[$offset]["isEdited"] = true;
                $this->table[$offset]["isDeleted"] = false;
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

    public function showUsersReferences($user)
    {
        $index = 0;
        $rowsToShow = Array();
        for ($i=0; $i < $this->count(); $i++)
            if ( $this->table[$i]['row']['userid'] == $user["userid"])
                $rowsToShow[$index++] =  $this->table[$i]['row'];
        return $rowsToShow;
    }

    /**
     * @param $offset integer
     * @return boolean
     */
    public function deleteRow($offset)
    {
        if ($offset >= 0 && $offset < $this->count){
            if (is_array($this->table) && array_key_exists($offset, $this->table)) {
                $this->table[$offset]["isDeleted"] = true;
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


    public function FindInitialReference($shortedRef) {
        $queryResult = $this->connection->query("select initialRef
										         from ref
										         where shortedRef==$shortedRef");
        return $queryResult->fetch_array()['initialRef'];
    }
    /*public function CreateShortenRef($initialRef) {
        $this->connection->query("select shortedRef
										         from ref
										         where initialRef==$initialRef");
    }*/
}
?>