<?php
namespace Models;
class ReferenceService
{
    private $lastID = 0;
    private $count = 0;
    private $table = Array();

    public function __construct($table, $counter, $lastID) {
        $this->count = $counter;
        $this->table = $table;
        $this->lastID = $lastID;
    }
    public function GetLastID()
    {
        return  $this->lastID;
    }

    public  function GetArray()
    {
        return $this->table;
    }

   public function AddRow(array $row)
    {
            $newRow = Array("refid" => $row[0], "userid" => $row[1], "initialRef" => $row[2], "shortedRef" => $row[3], "title" => $row[4], "date" => $row[5], "count" => $row[6]);
            $this->table[$this->count] = Array( "row" => $newRow, "isAdded" => true, "isEdited" => false, "isDeleted"=> false);
            $this->count++;
    }

    public function UpdateRow($id) {
        for ($i = 0; $i < $this->count();$i++)
            if ($this->table[$i]['row']['refid'] == $id) {
                $this->table[$i]["isEdited"] = true;
                $this->table[$i]["row"]["count"]++;
                break;
            }
    }

    public function GetRow($offset, $user)
    {
        for ($i=0; $i < $this->count(); $i++)
            if ( $this->table[$i]['row']['refid'] == $offset && $this->table[$i]['row']['userid'] == $user["userid"])
            return $this->table[$i]["row"];
            echo "У вас не создано такой ссылки!\n";
            exit;
    }

    public function ShowUsersReferences($user)
    {
        $index = 0;
        $rowsToShow = Array();
        for ($i=0; $i < $this->count(); $i++)
            if ( $this->table[$i]['row']['userid'] == $user["userid"])
                $rowsToShow[$index++] =  $this->table[$i]['row'];
        return $rowsToShow;
    }

    public function DeleteRow($offset, $user)
    {
        for ($i=0; $i < $this->count(); $i++)
            if ( $this->table[$i]['row']['refid'] == $offset && $this->table[$i]['row']['userid'] == $user["userid"]) {
                $this->table[$i]["isDeleted"] = true;
                return true;
            }
        return false;
    }

    public function GetRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++)
            $tableWithValues[$i] = $this->table[$i]["row"];
        return $tableWithValues;
    }

    public function Count() {
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
