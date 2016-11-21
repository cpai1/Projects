<?php
session_start();

require 'vendor/autoload.php';
if ((isset($_SESSION['link'])) && (isset($_SESSION['path']))){
$filelink=($_SESSION['link']);
echo "<center><h1>WELCOME    " . $filelink . "</h1></center>";
$filepath=($_SESSION['path']);
echo "<center><h1>WELCOME    " . $filepath . "</h1></center>";



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
echo "\n". "This is your URL: " . $url ."\n";
}
?>

<html>
<head><title>Hello app</title>
</head>
<body>
<h1>Love u</h1>
</body>
</html>