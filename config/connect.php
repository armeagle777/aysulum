<?php
$servername = "localhost";
$database = "aysulum";
$username = "root";
$password = "";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

$conn->set_charset('utf8mb4');
// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

//Gmail login migration.service.ra@gmail.com
//Gmail pass @AtUHxJw%Q6y 
//Gmail phone 033275024 
$gmail_login='migration.service.ra@gmail.com';
$gmail_pass='@AtUHxJw%Q6y';
$gmail_port=587; 
$gmail_host='smtp.gmail.com';
$mail_subject='Request for Interpretation/Translation Service';
$mail_body='<p>Խնդրում ենք հաստատել խնդրագիրը</p>';
?>