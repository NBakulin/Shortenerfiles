<?php
class RedirectCounterModel extends RedirectRepository
{
    private $count= 0;
    private $redirectTable = Array();


    public function __construct() {
        parent::__construct();
    }

    public function createRedirectDate($refid, $leftReference)
    {
        $this->redirectTable[$this->count] = Array( "row"=>["redirectid" => "is generating", "leftReference" => $leftReference,"date" =>  date_create('now')->format('Y\-m\-d\ h:i:s'), "refid" =>$refid], "isAdded"=>  true);
        $this->count++;
    }

    public function groupByMinutes($from_date, $to_date, $refid)
    {
        $returnDates = Array();
        $from_date .= ' 00:00:00';  $to_date .= ' 23:59:59';
        $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d %H:%i\"), count(date) 
                                                        from refDates where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d%H%i\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

    public function groupByHours($from_date, $to_date, $refid)
    {
        $returnDates = Array();
        $from_date .= ' 00:00:00';  $to_date .= ' 23:59:59';
        $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d %H\"), count(date) 
                                                        from refDates where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d%H\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

    public function groupByDays($from_date, $to_date, $refid)
    {
        $returnDates = Array();
        $from_date .= ' 00:00:00';  $to_date .= ' 23:59:59';
        $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d\"), count(date) 
                                                        from refDates where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

    public function getTop20()
    {
                $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d\"), count(date) 
                                                        from ref where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
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

  /*  public  function load()
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
    }*/

    public function getRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++){
            $tableWithValues[$i] = $this->redirectTable[$i]['row'];
        }
        return $tableWithValues;
    }

   /* public  function save()
    {
        for ($i=0; $i < $this->count(); $i++) {
            if ($this->redirectTable[$i]["isAdded"] == true){
                    $this->connection->query("insert into refDates values 
					(null,                                              '".
                    $this->redirectTable[$i]['row']['leftReference']."', '".
                    $this->redirectTable[$i]['row']['date']         ."', '".
                    $this->redirectTable[$i]['row']['refid']        ."')");
            }
        }
    }*/

}