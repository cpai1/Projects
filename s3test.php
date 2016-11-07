<?php

require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$bucket='itmo-chan';
$resultput= $s3->putObject(array(
    'Bucket' => $bucket,
    'Key' => 'switchonarex.png',
    'Body' => 'Hello!',
	'region'  => 'us-west-2',
	'ACL'=>'public-read'
));

echo $result['ObjectURL'] . "\n";
$display=$resultput['ObjectURL'];
$result = $s3->listBuckets();

foreach ($result['Buckets'] as $bucket) {
echo $bucket['Name'] . "\n";
}
$array = $result->toArray();
?>

<html>
<head>
<title>Image</title>
</head>
<body>
<img src= "<? echo $display; ?>" >
</body>
</html>
