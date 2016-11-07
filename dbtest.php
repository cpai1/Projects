<?php
require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'region'  => 'us-west-2'
));



$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-chandudb',
));


$endpoint = ""; 


foreach ($result->getPath('DBInstances/*/Endpoint/Address') as $ep) {
    // Do something with the message
    echo "============". $ep . "================";
    $endpoint = $ep;
}

echo "begin database";
$link = mysqli_connect("AWS URL *.us-west-2.rds.amazonaws.com","chandudb","chandu123","School") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* Insert statement */
$stmt =$db->stmt_init();
$stmt->prepare("INSERT INTO student (name,age) VALUES(?, ?)");
foreach($myarray as $row)
{
    $stmt->bind_param('idsb', $row['fld1'], $row['fld2'])
    $stmt->execute();
}
$stmt->close();

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

printf("%d Row inserted.\n", $stmt->affected_rows);


/* explicit close recommended */
$stmt->close();

$link->real_query("SELECT * FROM student");
$res = $link->use_result();

echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo " id = " . $row['id'] . "\n";
}


$link->close();




?>
