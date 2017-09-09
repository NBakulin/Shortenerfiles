<?php
namespace Models;
//use Repository\UserRepository;
class UserModel// extends UserRepository
{
    private $count= 0;
    private $userTable = Array();


   public function __construct($table) {
        $this->userTable = $table;
    }

    public function createUser(array $row)
    {
        $newRow = Array("userid" => $row[0], "email" => $row[1], "login" => $row[2], "name" => $row[3], "password" => password_hash( $row[4], PASSWORD_BCRYPT));
        $this->userTable[$this->count] = Array(
            "row" => $newRow,
            "isAdded" => true,
            "isEdited" => false,
            "isDeleted"=>   false);
        $this->count++;
    }

    public function getUserByLogin($login)
    {
        for ($i = 0; $i < $this->count; $i++)
        {
            if ($this->userTable[$i]['row']["login"] ==  $login)
            {
                return $this->userTable[$i]['row'];
            }
        }
        return null;
    }

    public function updateUser(User $user, $newEmail, $newName)
    {
        for ($i = 0; $i < $this->count; $i++)
        {
            if ($this->userTable[$i]['row']->GetUserID() ==  $user->GetUserID())
            {
                $this->userTable[$i]['row']->SetName($newName);
                $this->userTable[$i]['row']->SetMail($newEmail);
            }
            else return null;
        }
        return $user;

    }
    public function count() {
        return $this->count;
    }

   /* public  function load()
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
    }*/

    public function getRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++){
                        $tableWithValues[$i] = $this->userTable[$i]['row'];
        }
        return $tableWithValues;
    }

  /*  public  function save()
    {
        for ($i=0; $i < $this->count(); $i++) {
            if ($this->userTable[$i]["isAdded"] == true){
                $this->connection->query("insert into user values 
												(null, '".
                    $this->userTable[$i]['row']['email'].        "', '".
                    $this->userTable[$i]['row']['login'].        "', '".
                    $this->userTable[$i]['row']['name'].         "', '".
                    $this->userTable[$i]['row']['password'].     "')");
              }
        }
    }*/


    public function getUserByBasicAuth()
    {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $user = UserService::getUserByLogin($username);
        $hash = password_verify($password, $user['password']);
        if ($user != null && $user["password"] == $hash) {
            return $user;
        } else {
            return null;
        }
    }

    }