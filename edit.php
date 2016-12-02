<?php
session_start();
require 'vendor/autoload.php';



$sqsclient = new Aws\Sqs\SqsClient([
    'region'  => 'us-west-2',
    'version' => 'latest'
]);

// Code to retrieve the Queue URLs
$sqsresult = $sqsclient->getQueueUrl([
    'QueueName' => 'chanduQueue', // REQUIRED
]);

$queueUrl = $sqsresult['QueueUrl'];
echo $queueUrl;

$sqsresult = $sqsclient->receiveMessage([
    'QueueUrl' => $queueUrl,
]);

echo $sqsresult;

$messageBody=$sqsresult['Messages'][0]['Body'];

  echo $messageBody;
$receipthandler=$sqsresult['Messages'][0]['ReceiptHandle'];
echo $receipthandler;
$client = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest'
]);


$result = $client->describeDBInstances([
    'DBInstanceIdentifier' => 'itmo-chandu'
]);


$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
echo $endpoint . "\n";
$link = mysqli_connect($endpoint,"controller","iloveuchandu","login") or die("Error
 " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql= "SELECT * FROM items WHERE receipt='$messageBody'";
$result = $link->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " email: " . $row["email"]. "<br>";
$rawurl=$row['s3rawurl'];
echo $rawurl;
$phoneno=$row['phone'];
echo $phoneno;
$stamp = imagecreatefrompng('https://s3-us-west-2.amazonaws.com/cpai1-itm-logo/IIT-logo.png');
$im = imagecreatefromjpeg($rawurl);

//Set the margins for the stamp and get the height and width of the stamp image
$marge_right=10;
$marge_bottom=10;
$sx = imagesx($stamp);
$sy = imagesy($stamp);
echo $sy . "\n";
imagecopy($im,$stamp,imagesx($im) - $sx -$marge_right, imagesy($im) - $sy -$marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
imagepng($im,'/tmp/rendered.png');
imagedestroy($im);
$s3raw = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$bucket='finished-cai';
$imgpath='/tmp/rendered.png';
$s3result = $s3raw->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'region'  => 'us-west-2',
    'Body' => 'Hello!',
    'Key' => basename($imgpath),
     'SourceFile' =>$imgpath,


// Retrieve URL of uploaded Object
]);
$furl=$s3result['ObjectURL'];
echo $furl;
$fsql = "UPDATE items SET  s3finishedurl='$furl',status=1 WHERE receipt='$messageBody'";

if ($link->query($fsql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $link->error;
}
$snsclient = new Aws\Sns\SnsClient(array(
    'version' => 'latest',
    'region'  => 'us-west-2'
));
$snsresult = $snsclient->publish(array(
    'TopicArn' => 'arn:aws:sns:us-west-2:252652150750:my-message',
    'Message' => 'Success!',
    'Subject' => 'Update result',
    'MessageStructure' => 'string'

));

echo "topic published!";

echo "subscribe";
$snsresult = $snsclient->subscribe(array(

    'TopicArn' => 'arn:aws:sns:us-west-2:252652150750:my-message',
    'Protocol' => 'sms',
    'Endpoint' => $phoneno,
));

    }
} else {
    echo "0 results";
}

$link->close();

echo "Result set order";

?>