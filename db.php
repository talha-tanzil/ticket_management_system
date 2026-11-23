<?php
$username = "root";
$password = "";
$hostname = "localhost";
$dbname = "ticket_management";
$conn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// if($conn){
//     echo 'Connection Success';
// }
