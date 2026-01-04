<?php

//Start Session
session_start();

//Create Constants to Store Non Repeating Values
define('SITEURL','http://localhost/Food-order/');
define('LOCALHOST','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','food-order');

//3.Execute Query and Save Data in Database

$conn=mysqli_connect('localhost','root','')or die(mysqli_error());//Database Connection
$db_select=mysqli_select_db($conn,'food-order')or die(mysqli_error());//Selecting Database
?>