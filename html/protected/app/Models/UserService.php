<?php
require_once('Connector.php');
class UserService extends Connector
{
    private $count= 0;
    private $userTable = Array();


    public function __construct() {
        parent::__construct();
    }

    public function createUser(array $row)
    {
        if (is_numeric($row[0])) {
            $newRow = Array("userid" => $row[0], "email" => $row[1], "login" => $row[2], "name" => $row[3], "password" => password_hash( $row[4], PASSWORD_BCRYPT));
            $this->userTable[$this->count] = $newRow;
            $this->count++;
        }
        else echo "Неверный формат входных данных, выводим БД без изменений! \n";
    }

    public function getUserByLogin($login)
    {
        for ($i = 0; $i < $this->count; $i++)
        {
            if ($this->userTable[$i]["login"] ==  $login)
            {
                return $this->userTable[$i];
            }
        }
        return null;
    }

    public function updateUser(User $user, $newEmail, $newName)
    {
        for ($i = 0; $i < $this->count; $i++)
        {
            if ($this->userTable[$i]->GetUserID() ==  $user->GetUserID())
            {
                $this->userTable[$i]->SetName($newName);
                $this->userTable[$i]->SetMail($newEmail);
            }
            else return null;
        }
        return $user;

    }
    public function count() {
        return $this->count;
    }

    public  function load()
    {
        $queryResult =  $this->connection->query("select * from user");
        if (!$queryResult) {
            throw new Exception('Query result is null');
        }
        for ($i = 0; $row=$queryResult->fetch_array(MYSQLI_ASSOC); $i++) {
            $this->userTable[$i] =  $row;
            $this->count++;
        }
    }

    public function getRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++)
            $tableWithValues[$i] = $this->userTable[$i];
        return $tableWithValues;
    }
    /**
     * @return void
     */
    public function save()
    {
        $queryResult =  $this->connection->query("delete from user");
        for ($i=0; $i < $this->count(); $i++) {
            $queryResult =  $this->connection->query("insert into user values 
										      (null, '".
                $this->userTable[$i]["email"].      "', '".
                $this->userTable[$i]["login"].     "', '".
                $this->userTable[$i]["name"].      "', '".
                $this->userTable[$i]["password"].  "')");
        }
    }

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