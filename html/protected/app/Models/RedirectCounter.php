<?php
require_once('Connector.php');
class RedirectCounter extends Connector
{
    private $count= 0;
    private $redirectTable = Array();


    public function __construct() {
        parent::__construct();
    }

    public function createRedirectDate($refid)
    {
        $this->redirectTable[$this->count] = Array( "row"=>["redirectid" => "is generating", "date" =>  date_create('now')->format('Y\-m\-d\ h:i:s'), "refid" =>$refid], "isAdded"=>  true);
        $this->count++;
    }

    public function groupByDays($from_date, $to_date)
    {
        $returnDates = Array();
        //$from_date = date_create($from_date);
        //$to_date = date_create($to_date);
        $queryResult = $this->connection->query("select date, count(date)
                                         from refDates
                                         where refid=2 and date>$from_date and date<$to_date
                                         group by minute(date)");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $i++) {
            $this->$returnDates[$i] =  $row;
        }
        return $returnDates;

            /*if (var_dump($this->redirectTable[i]["row"]["date"] >= $datefirst && $this->redirectTable[i]["row"]["date"] <= $datesecond))
                {
                    $interval = $datetime1->diff($datetime2);
                }*/


    }

    public function getRedirectDate($login)
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

    public function count() {
        return $this->count;
    }

    public  function load()
    {
        $queryResult =  $this->connection->query("select * from refDates");
        if (!$queryResult) {
            throw new Exception('Query result is null');
        }
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $i++) {
            $this->redirectTable[$i] = Array(
                "row"=>       $row,
                "isAdded"=>   false);
            $this->count++;
        }
    }

    public function getRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++){
            $tableWithValues[$i] = $this->redirectTable[$i]['row'];
        }
        return $tableWithValues;
    }

    public  function save()
    {
        for ($i=0; $i < $this->count(); $i++) {
            if ($this->redirectTable[$i]["isAdded"] == true){
                $this->connection->query("insert into refDates values 
												(null, '".
                    $this->redirectTable[$i]['row']['date'].        "', '".
                    $this->redirectTable[$i]['row']['refid'].       "')");
            }
        }
    }

}