<?php

require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$result = $s3->listBuckets();

foreach ($result['Buckets'] as $bucket) {
echo $bucket['Name'] . "\n";
}
$array = $result->toArray();
$bucket='itmo-chan';
$Imagepath='/var/www/html/switchonarex.png';

$resulturl= $s3->putObject(array(
    'Bucket' => $bucket,
    'Key' => 'switchonarex.png',
    'Body' => 'Hello!',
	'SourceFile' => $Imagepath,
	'region'  => 'us-west-2',
	'ACL'=>'public-read'
));

$display=$resulturl['ObjectURL'];
?>

<html>
<body>
<h1><?php echo $display; ?><h1>
<img src="<?php echo $display; ?>" height="500" width="600">
</body>
</html>
