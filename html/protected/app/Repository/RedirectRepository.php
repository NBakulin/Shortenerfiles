<?php
namespace Repository;
require_once('ConnectorModel.php');
class RedirectRepository extends Connector
{
    private $count= 0;
    private $redirectTable = Array();


    public function __construct() {
        parent::__construct();
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

    public  function save()
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
    }

}