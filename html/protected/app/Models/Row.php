<?php
namespace App;
require 'TableModelInterface.php';
class Row
{
    private $id;
    private $userId;
    private $title;
    private $initialRef;
    private $shortedRef;
    private $registrationDate;
    private $followersCount;

    public function __construct( $id, $userId, $title, $initialRef, $shortedRef, $registrationDate, $followersCount){
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->initialRef = $initialRef;
        $this->shortedRef = $shortedRef;
        $this->registrationDate = $registrationDate;
        $this->followersCount = $followersCount;
    }
    public function getId(){
        return $this->id;
    }
    public function getUserId(){
        return $this->userId;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getInitialRef(){
        return $this->initialRef;
    }
    public function getShortedRef(){
        return $this->shortedRef;
    }
    public function getRegistrationDate(){
        return $this->registrationDate;
    }
    public function getFollowersCount(){
        return $this->followersCount;
    }
}
?>