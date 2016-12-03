<?php

echo "Upload Feature On or Off";

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
echo "Congratulations backup your database done!";
header( "refresh:3;url=gallery.php" );
?>



<html>
<head>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
</head>
<body>



<label class="switch">
  <input type="checkbox" checked>
  <div class="slider round"></div>
</label>

</body>
</html>
