<?php
session_start();

require 'vendor/autoload.php';
require 'connect.php';

if ((isset($_SESSION['link'])) && (isset($_SESSION['path'])) && (isse
$filelink=($_SESSION['link']);

$filepath=($_SESSION['path']);

$username = $_SESSION['username'];
echo "<center><h1>WELCOME    " . $username . "</h1></center>";


$s3raw = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$resultraw = $s3raw->listBuckets();

foreach ($resultraw['Buckets'] as $bucket) {
echo $bucket['Name'] . "\n";
}
$array = $resultraw->toArray();

$bucket='raw-cai';
$s3result = $s3raw->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'region'  => 'us-west-2',
    'Body' => 'Hello!',
    'Key' =>  basename($filepath),
    'SourceFile' => $filelink


// Retrieve URL of uploaded Object
]);
$url=$s3result['ObjectURL'];
$create_table = 'CREATE TABLE IF NOT EXISTS items
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    s3rawurl VARCHAR(255),
    s3finishedurl VARCHAR(255),
    status INT(1),
    issubscribed INT(2),
    receipt VARCHAR(255),
	PRIMARY KEY(id)
)';
$create_tbl = $link->query($create_table);
if ($create_table) {
        echo "Table is created or No error returned.";
}
else {
        echo "error!!";
}

echo "begin database";


$query = $link->prepare("INSERT INTO items(email,phone,s3rawurl,s3finishedurl,status,issubscribed,receipt) VALUES (?,?,?,?,?,?,?
)");

$email=$username;
$phone='4696644548';
$s3rawurl=$url;
$s3finishedurl='';
$status=0;
$issubscribed=0;
$receipt=md5($url);
echo $id;
echo $email;
echo $phone;
echo $s3rawurl;
echo $s3finishedurl;

echo "  ". $receipt . "<br>";


// prepared statements will not accept literals (pass by reference) in bind_params, you need to declare variables
$query->bind_param('ssssiis', $email, $phone, $s3rawurl, $s3finishedurl, $status, $issubscribed, $receipt);


$query->execute();
printf("       %d Row inserted.\n", $link->affected_rows);

$link = mysqli_connect("itmo-chandu.cosqt4a6q7en.us-west-2.rds.amazonaws.com","controller","iloveuchandu","login") or die("Error
 " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$link->real_query("SELECT * FROM items");
$res = $link->use_result();
   echo "<br/>";
   echo "<table>";
   echo "<tr>";
   echo "<th> ID </th>";
   echo "<th> email </th>";
   echo "<th> phone </th>";
   echo "<th> s3rawurl </th>";
   echo "<th> s3finishedurl </th>";
   echo "<th> status </th>";
   echo "<th> subscribe </th>";
   echo "<th> receipt </th>";
   echo "</tr>";
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>";
    echo $row['id'];
    echo "</td>";
    echo "<td>";
    echo $row['email'];
    echo "</td>";
    echo "<td>";
    echo $row['phone'];
    echo "</td>";
    echo "<td>";
   echo $row['s3rawurl'];
   echo "</td>";
   echo "<td>";
   echo $row['s3finishedurl'];
   echo "</td>";
   echo $row['status'];
    echo "</td>";
    echo "<td>";
    echo $row['issubscribed'];
    echo "</td>";
    echo "<td>";
   echo $row['receipt'];
   echo "</td>";
   echo "</tr>";
}

$link->close();
echo "\n". "This is your URL: " . $url ."\n";
$sqsclient = new Aws\Sqs\SqsClient([
    'region'  => 'us-west-2',
    'version' => 'latest'
]);

// Code to retrieve the Queue URLs
$sqsresult = $sqsclient->getQueueUrl([
    'QueueName' => 'chanduQueue', // REQUIRED
]);

echo $sqsresult['QueueUrl'];

$queueUrl = $sqsresult['QueueUrl'];

$sqsresult = $sqsclient->sendMessage([
   // REQUIRED
    'MessageBody' => $receipt,
    'QueueUrl' => $queueUrl // REQUIRED
]);

echo $sqsresult['MessageId'];
}
?>

<html>
<head><title>Hello app</title>
</head>
<body>
<h1>Love u</h1>
</body>
</html>