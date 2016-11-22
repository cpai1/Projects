<?php
session_start();

require 'vendor/autoload.php';
require 'connect.php';
if (isset($_SESSION['username'])){
$email = $_SESSION['username'];
echo "<center><h1>WELCOME    " . $username . "<br>";



//below line is unsafe - $email is not checked for SQL injection -- don't do this in real life or use an ORM instead
$link->real_query("SELECT * FROM items WHERE email = '$email'");
//$link->real_query("SELECT * FROM items");
$res = $link->use_result();

while ($row = $res->fetch_assoc()) {

    echo "<img src =\" " . $row['s3rawurl'] . "\" />";
echo $row['id'] . "Email: " . $row['email'];
}
$link->close();
}
?>
<html>
<head>
<style>
 img {
       float: left; width: 30%; margin-right: 1%; margin-bottom: 0.5em;
      }
</style>
</head>
</html>