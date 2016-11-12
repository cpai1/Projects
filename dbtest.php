<?php

require 'vendor/autoload.php';
use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo-chandudb',
));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print_r($endpoint);

echo "begin database";
$link = mysqli_connect($endpoint,"chandudb","chandu123","School",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$create_table = 'CREATE TABLE IF NOT EXISTS Students
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    age INT(3) NOT NULL,
    PRIMARY KEY(id)
)';

$create_tbl = $link->query($create_table);
if ($create_table) {

        echo "<b>Table created successfully</b>";

}
else {
        echo "There is an error!!";
}

#Insert data

$sql = "INSERT INTO Students (name, age) VALUES ('Chan',26), ('karthik', 24), ('akhil',25), ('anusha', 26), ('adi',26)";
if ($link->query($sql) === TRUE) {
    echo "Record Inserted successfully";

} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

#display data

$link->real_query("SELECT * FROM Students");
$res = $link->use_result();
   echo "<br/>";
   echo "<table>";
   echo "<tr>";
   echo "<th> ID </th>";
   echo "<th> Name </th>";
   echo "<th> Age </th>";
   echo "</tr>";
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>";
    echo $row['id'];
    echo "</td>";
    echo "<td>";
    echo $row['name'];
    echo "</td>";
    echo "<td>";
    echo $row['age'];
    echo "</td>";
    echo "</tr>";
}


$link->close();
?>






































































































