<?php
namespace Repository;
require_once('BaseRepository.php');
class UserRepository extends BaseRepository
{
    private $count= 0;
    private $userTable = Array();


    public function __construct() {
        parent::__construct();
    }

       public  function load()
    {
        $queryResult =  $this->connection->query("select * from user");
        if (!$queryResult) {
            throw new Exception('Query result is null');
        }
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $i++) {
            $this->userTable[$i] = Array(
                "row"=>       $row,
                "isAdded"=>   false,
                "isEdited"=>   false,
                "isDeleted"=>   false);
            $this->count++;
        }
    }

    public  function save($userTable, $count)
    {
        echo json_encode($userTable, JSON_PRETTY_PRINT);
        for ($i=0; $i < $count; $i++) {
            if ($userTable[$i]["isAdded"] == true){
                $this->connection->query("insert into user values 
												(null, '".
                    $userTable[$i]['row']['email'].        "', '".
                    $userTable[$i]['row']['login'].        "', '".
                    $userTable[$i]['row']['name'].         "', '".
                    $userTable[$i]['row']['password'].     "')");
            }
        }
    }

    public  function count()
    {
        return $this->count;
    }

    public  function GetArray()
    {
        return $this->userTable;
    }

}