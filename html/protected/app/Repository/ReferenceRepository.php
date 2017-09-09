<?php
require_once('ConnectorModel.php');
class ReferenceRepository extends Connector
{
    private $lastID = 0;
    private $count = 0;
    private $table = Array();

    public function __construct() {
        parent::__construct();
    }

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
            if ($this->table[$i]["isEdited"] == true){
                $refid = $this->table[$i]['row']['refid'];
                $this->connection->query("update ref 
                                                set count = count + 1
                                                where refid = $refid");
            }
        }

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


}
