<?php
namespace Models;
class ReferenceModel
{
    private $lastID = 0;
    private $count = 0;
    private $table = Array();

    public function __construct($table, $counter, $lastID) {
        $this->count = $counter;
        $this->table = $table;
        $this->lastID = $lastID;
    }
    public function getLastID()
    {
        return  $this->lastID;
    }

    public  function GetArray()
    {
        return $this->table;
    }

    public function addRow(array $row)
    {
            $newRow = Array("refid" => $row[0], "userid" => $row[1], "initialRef" => $row[2], "shortedRef" => $row[3], "title" => $row[4], "date" => $row[5], "count" => $row[6]);
            $this->table[$this->count] = Array(
                "row" => $newRow,
                "isAdded" => true,
                "isEdited" => false,
                "isDeleted"=>   false);
            $this->count++;
    }

    public function updateRow($id) {
        for ($i = 0; $i < $this->count();$i++)
            if ($this->table[$i]['row']['refid'] == $id) {
                $this->table[$i]["isEdited"] = true;
                $this->table[$i]["row"]["count"]++;
                break;
            }
    }

    public function getRow($offset, $user)
    {
        for ($i=0; $i < $this->count(); $i++)
            if ( $this->table[$i]['row']['refid'] == $offset && $this->table[$i]['row']['userid'] == $user["userid"])
            return $this->table[$i]["row"];
            echo "У вас не создано такой ссылки!\n";
            exit;
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

    public function deleteRow($offset, $user)
    {
        echo $this->table[58]["row"]["isDeleted"];
        for ($i=0; $i < $this->count(); $i++)
             if ( $this->table[$i]['row']['refid'] == $offset && $this->table[$i]['row']['userid'] == $user["userid"]) {
                echo $this->table[$i]["row"]["isDeleted"] = true;
                return true;
            }
            else
                return false;
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

    public function count() {
        return $this->count;
    }

    public function FindInitialReference($shortedRef)
    {
        for ($i = 0; $i < $this->count(); $i++) {
            if ($this->table[$i]['row']['shortedRef'] == $shortedRef) {
                return $this->table[$i]['row'];
            }
        }
        return null;
    }
}
