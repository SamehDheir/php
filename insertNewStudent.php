<?php
include 'db_helper.php';
header("Content-Type:application/json");
$db_helper = new DbHelper();
$db_helper->createDbConnection();
if($_SERVER["REQUEST_METHOD"]=="POST"){
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$age = $_POST["age"];

$db_helper->insertNewStudent($name,$email,$password,$age);
}
?>