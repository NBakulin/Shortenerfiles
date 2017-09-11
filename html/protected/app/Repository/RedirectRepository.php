<?php
namespace Repository;
class RedirectRepository extends BaseRepository
{
    private $count= 0;
    private $redirectTable = Array();


    public function __construct() {
        parent::__construct();
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
    }


    public  function Save($redirectTable, $count)
    {
        for ($i=0; $i < $count; $i++) {
            if ($redirectTable[$i]["isAdded"] == true){
                $this->connection->query("insert into refDates values 
					(null,                                              '".
                    $redirectTable[$i]['row']['leftReference']."', '".
                    $redirectTable[$i]['row']['date']         ."', '".
                    $redirectTable[$i]['row']['refid']        ."')");
            }
        }
    }

    public function GroupByMinutes($from_date, $to_date, $refid)
    {
        $returnDates = Array();
        $from_date .= ' 00:00:00';  $to_date .= ' 23:59:59';
        $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d %H:%i\"), count(date) 
                                                        from refDates where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d%H%i\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date yyyy-mm-dd hh:mm" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

    public function GroupByHours($from_date, $to_date, $refid)
    {
        $returnDates = Array();
        $from_date .= ' 00:00:00';  $to_date .= ' 23:59:59';
        $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d %H\"), count(date) 
                                                        from refDates where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d%H\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date yyyy-mm-dd hh" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

    public function GroupByDays($from_date, $to_date, $refid)
    {
        $returnDates = Array();
        $from_date .= ' 00:00:00';  $to_date .= ' 23:59:59';
        $queryResult = $this->connection->query("select date_format(date, \"%Y-%m-%d\"), count(date) 
                                                        from refDates where refid=$refid and date> '$from_date' and date< '$to_date' 
                                                        group by date_format(date, \"%Y%m%d\");");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["Date yyyy-mm-dd" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

   public function GetTop20($refid)
    {
        $returnDates = Array();
        $queryResult = $this->connection->query("select leftReference, count(leftReference)
                                                        from refDates where refid=$refid
                                                        group by leftReference
                                                        order by count(leftReference) desc
                                                        limit 20;");
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_NUM); $i++) {
            $returnDates[$i] = ["reference" => $row[0], "Count" =>  $row[1]];;
        }
        return $returnDates;
    }

    public  function GetArray(){
        return $this->redirectTable;
    }

    public function Count() {
        return $this->count;
    }
}