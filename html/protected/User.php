<?php
class User {
    private $UserID;
    private $Login;
    private $Mail;
    private $Name;
    private $Password;

    public function __construct($UserID, $Login, $Mail, $Name, $Password){
        $this->Login = $Login;
        $this->Mail = $Mail;
        $this->Name = $Name;
        $this->Password = $Password;
        $this->UserID = $UserID;
    }
    public function GetUserID(){
        return $this->UserID;
    }
    public function GetLogin(){
        return $this->Login;
    }
    public function GetMail(){
        return $this->Mail;
    }
    public function GetName(){
        return $this->Name;
    }
    public function GetPassword(){
        return $this->Password;
    }
    public function SetMail($newMail){
        $this->Mail = $newMail;
    }
    public function SetName($newName){
        $this->Name = $newName;
    }

}