#!/bin/bash

dbname="chandudb"
dbidentifier="itmo-chandudb"
dbsgrp="sg-8f6fa4f6"
dbinstance="db.t2.micro"
dbengine="mysql"
muser="chandudb"
mpassword="chandu123"
storage="5"
dbzone="us-west-2b"

#creating the rds instance

aws rds create-db-instance --db-name $dbname --db-instance-identifier $dbidentifier --vpc-security-group-ids $dbsgrp --allocated-storage $storage --db-instance-class $dbinstance --engine $dbengine --master-username $muser --master-user-password $mpassword --availability-zone $dbzone

aws rds wait db-instance-available --db-instance-identifier $dbidentifier

echo "RDS instance successfully created"
