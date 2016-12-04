<?php
session_start();
echo "</br>";


require 'vendor/autoload.php';

#http://www.tutorialspoint.com/php/perform_mysql_backup_php.htm
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'itmo-chandu',
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

$link = mysqli_connect($endpoint,"controller","iloveuchandu","login") or die("Error " . mysqli_error($link));

$dbname = 'login';
$dbuser = 'controller';
$dbpass = 'iloveuchandu';
mkdir("/tmp/CPAIDTBC");
$Backuppath = '/tmp/CPAIDTBC/';
$backname = uniqid("CPAI", false);
$move = $backname . '.' . sql;
$Path = $Backuppath . $move;
$sql="mysqldump --user=$dbuser --password=$dbpass --host=$endpoint $dbname > $Path";
exec($sql);

$bucket = uniqid("cpai1-backup-data",false);
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket
]);
$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $move,
'SourceFile' => $Path,
]);

$objectrule = $s3->putBucketLifecycleConfiguration([
    'Bucket' => $bucket,
    'LifecycleConfiguration' => [
        'Rules' => [
            [
                'Expiration' => [
                    'Days' => 1,
                ],
                'NoncurrentVersionExpiration' => [
                    'NoncurrentDays' => 1,
                ],
                'Prefix' => ' ',
                'Status' => 'Enabled',

            ],

        ],
    ],
]);

$link->close();
echo "Backup database done"
?>



<html>
<head>
</head>
<body>
<form action="welcome.php" method='post'>
<select name="flag">
<option value="0">OFF</option>
<option value="1">ON</option>
</select>
<input type="submit" />
</form>
</body>
</html>
