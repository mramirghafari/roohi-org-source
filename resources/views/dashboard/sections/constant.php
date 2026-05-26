<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trade_db";

$conn = mysqli_connect($servername,$username,$password,$dbname);
if(!$conn){
    die("Can't Connetct to Database");
}