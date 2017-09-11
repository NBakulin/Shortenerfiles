<?php
$userToShow = ["User's ID   "=>$user["userid"], "User's login"=>$user["login"], "User's email"=>$user["email"], "User's name "=>$user["name"]];
echo json_encode($userToShow, JSON_PRETTY_PRINT);
