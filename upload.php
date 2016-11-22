<?php

session_start();
require 'vendor/autoload.php';

if (isset($_SESSION['username'])){
$username = $_SESSION['username'];
echo "<center><h1>WELCOME    " . $username . "</h1></center>";
}
if(isset($_FILES['userfile'])){
      $errors= array();
      $file_name = $_FILES['userfile']['name'];
      $uploaddir = '/tmp/';
      $uploadfile = $uploaddir . basename($file_name);
      echo $uploadfile;
      $file_size =$_FILES['userfile']['size'];
      $file_tmp =$_FILES['userfile']['tmp_name'];
      $file_type=$_FILES['userfile']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['userfile']['name'])));

      $expensions= array("jpeg","jpg","png");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file";
      }

      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }

      if(empty($errors)==true){
         move_uploaded_file($file_tmp,$uploadfile);
         $_SESSION['link']=$uploadfile;
         //echo $_SESSION['link'];
         $_SESSION['path']=$file_name;
         //echo $_SESSION['path'];
         header("location:uploader.php");
         echo "Success";
      }else{
         print_r($errors);
      }
   }echo "<center><h2>You Can Now Upload The File Easily</h2></center>";

?>

<html>
<head><title>Best Page Ever</title></head>
<body>
<h1> World</h1>


<form enctype="multipart/form-data" action=" " method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->

    <input type="file" name="userfile" />
    <input type="submit" name="submit" value="Upload Image" />
</form>

</body>
</html>
