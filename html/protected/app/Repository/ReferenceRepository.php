<?php
namespace Repository;
class ReferenceRepository extends BaseRepository
{
    private $lastID = 0;
    private $count = 0;
    private $table = Array();

    public function __construct() {
        parent::__construct();
        $queryResult = $this->connection->query("select * from ref");
        if (!$queryResult) {
            throw new Exception('Query result is null');
        }
        $lastID = 0;
        for ($count = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $count++) {
            $this->table[$count] = Array("row"=> $row, "isAdded"=> false, "isEdited"=> false,"isDeleted"=>   false);
            $lastID = $count;
            $this->count++;
        }
         if (sizeof($this->table) > 0) $this->lastID =  $this->table[$lastID]["row"]["refid"] + 1;

    }

    public  function GetLastID() {
        return $this->lastID;
    }

    public  function Save($table, $count) {
        for ($i=0; $i <$count; $i++) {
            if ($table[$i]["isEdited"] == true){
                $refid = $table[$i]['row']['refid'];
                $this->connection->query("update ref 
                                                set count = count + 1
                                                where refid = $refid");
            }
        }

        for ($i=0; $i < $count; $i++) {
            if ($table[$i]["isAdded"] == true){
                $this->connection->query("insert into ref values 
												(null, '".
                    $table[$i]['row']['userid'].    "', '".
                    $table[$i]['row']['initialRef']."', '".
                    $table[$i]['row']['shortedRef']."', '".
                    $table[$i]['row']['title'].     "', '".
                    $table[$i]['row']['date'].      "', '".
                    $table[$i]['row']['count'].	  "')");
            }
        }

        $refid = 0;
        for ($i=0; $i < $count; $i++) {
            if ($table[$i]["isDeleted"] == true){
                $refid =$table[$i]['row']['refid'];
                $this->connection->query("delete from ref 
                                                where refid = $refid");
                unset($table[$i]);
                $this->count--;
            }
        }
        $this->connection->query("delete from refDates
                                        where refid = $refid");


    }

    public  function Count(){
        return $this->count;
    }

    public  function GetArray()
    {
        return $this->table;
    }

    public  function CheckExistance($initialRef, $user)  {
        for ($i=0; $i < $this->count; $i++)
            if ($this->table[$i]["row"]["initialRef"] == $initialRef && $this->table[$i]["row"]["userid"] === $user["userid"])
                return true;
            return false;
    }
}
