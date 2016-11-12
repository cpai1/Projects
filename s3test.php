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

$Imagepath='/var/www/html/switchonarex.png';
$bucket='itmo-chan';

$resulturl= $s3->putObject(array(
    'Bucket' => $bucket,
    'Key' => 'switchonarex.png',
    'region'  => 'us-west-2',
    'ACL'=>'public-read',
    'Body' => 'Hello!',
    'SourceFile' => $Imagepath
));

$display=$resulturl['ObjectURL'];
?>

<html>
<body>
<h1>Image</h1>
<img src="<?php echo $display; ?>" height="600" width="600">
</body>
</html>
