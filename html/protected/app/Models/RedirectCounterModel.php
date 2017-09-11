<?php
namespace Models;
class RedirectCounterModel
{
    private $count= 0;
    private $redirectTable = Array();


    public function __construct($table, $counter) {
        $this->count = $counter;
        $this->redirectTable = $table;
    }

    public function CreateRedirectDate($refid, $leftReference)
    {
        $this->redirectTable[$this->count] = Array( "row"=>["redirectid" => "is generating", "leftReference" => $leftReference,"date" =>  date_create('now')->format('Y\-m\-d\ h:i:s'), "refid" =>$refid], "isAdded"=>  true);
        $this->count++;
    }


    public function GetRedirectDate($login)
    {
        for ($i = 0; $i < $this->count; $i++)
        {
            if ($this->redirectTable[$i]['row']["login"] ==  $login)
            {
                return $this->redirectTable[$i]['row'];
            }
        }
        return null;
    }

    public function Count() {
        return $this->count;
    }



    public function GetRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++){
            $tableWithValues[$i] = $this->redirectTable[$i]['row'];
        }
        return $tableWithValues;
    }


    public function GetArray()
    {

        return $this->redirectTable;
    }

}