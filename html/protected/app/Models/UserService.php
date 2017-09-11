<?php
namespace Models;
class UserService
{
    private $count= 0;
    private $userTable = Array();


   public function __construct($table, $counter) {
        $this->count = $counter;
       $this->userTable = $table;
    }

    public function CreateUser(array $row)
    {
        $newUser = Array("userid" => null, "email" => $row["email"], "login" => $row["login"], "name" => $row["name"], "password" => password_hash( $row["password"], PASSWORD_BCRYPT));
        $this->userTable[$this->count] = Array( "row" => $newUser, "isAdded" => true, "isEdited" => false, "isDeleted"=>   false);
        $this->count++;
    }

    public function GetUserByLogin($login)
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

    public function Count() {
        return $this->count;
    }

    public function GetRows()
    {
        $tableWithValues = Array();
        for ($i=0; $i < $this->count(); $i++){
                        $tableWithValues[$i] = $this->userTable[$i]['row'];
        }
        return $tableWithValues;
    }


    public function GetArray()
    {
        return  $this->userTable;
    }

    public function GetUserByBasicAuth()
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
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
        else {
            echo "Заполнитя поля логин и/или пароль!";
            exit;
        }
    }
        public function CheckExistance($login, $email)
        {
            for ($i=0; $i < $this->count(); $i++) {
                if ($this->userTable[$i]['row']["login"] === $login || $this->userTable[$i]['row']["email"] === $email)
                    return true;
            }
                return false;

        }
    }