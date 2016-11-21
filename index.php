<?php

session_start();
require 'connect.php';
require 'vendor/autoload.php';



$create_table = 'CREATE TABLE IF NOT EXISTS Login_credentials
(
    id INT NOT NULL AUTO_INCREMENT,
    Username VARCHAR(200) NOT NULL,
    password VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
)';
$create_table = 'CREATE TABLE IF NOT EXISTS items
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    s3rawurl VARCHAR(255) NOT NULL,
    s3finishedurl VARCHAR(255) NOT NULL,
    status INT NOT NULL,
    issubscribed INT NOT NULL,
    receipt VARCHAR(256),
    PRIMARY KEY(id)
)';
$create_tbl = $link->query($create_table);
if ($create_table) {
        echo "Table is created or No error returned.";
}
else {
        echo "error!!";
}
$sql = "INSERT INTO Login_credentials (username, password) VALUES ('cpai1@hawk.iit.edu','admin'),('hajek@iit.edu','user')";
if ($link->query($sql) === TRUE) {
    echo "Record Inserted successfully";

} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}
if (isset($_POST['username']) and isset($_POST['password'])){
//Assigning posted values to variables.
$username = $_POST['username'];
$password = $_POST['password'];
//Checking the values are existing in the database or not
$query = "SELECT * FROM Login_credentials WHERE username='$username' and password='$password'";
print_r($query);
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$count = mysqli_num_rows($result);
print_r($result);
print_r($count);
//If the posted values are equal to the database values, then session will be created for the user.
if ($count > 0){
$_SESSION['username'] = $username;
echo $_SESSION['username'];
header("location:welcome.php");
}else{

//If the login credentials doesn't match, he will be shown with an error message.

echo  "<center><h3>Invalid Login Credentials<h3><br></center>";
}
}

$link->close();
?>
<html>
    <head>
      <title>Login Page</title>
        <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
    </head>
   <body bgcolor = "#F3F5C8">
<div align = "center">
          <div style = "width:300px; border: solid 1px #FF3924;background-color:#DADADA; " align = "left">
            <div style = "background-color:#7A00BF; color:#FFFFFF; padding:3px;"><b>Login</b></div>
            <div style = "margin:30px; background-color:#DADADA;" >
                <form action = "" method = "post" style = "color:#7A00BF;">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box" placeholder="Username"
required/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" placeholder="Passwo
rd" required/><br/><br />
                  <input type = "submit" value = " Login "/><br />

               </form>
            </div>
                </div>
</div>
    </body>
</html>













