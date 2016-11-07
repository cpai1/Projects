#!/bin/bash

dbname="Student"
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

#creating SNS:
savearn=`aws sns create-topic --name my-message`
aws sns subscribe --topic-arn $savearn --protocol email --notification-endpoint cpai1@hawk.iit.edu

#creating SQS:
saveurl=`aws sqs create-queue --queue-name chanduQueue` 
aws sqs send-message --queue-url $saveurl --message-body "Hello"
aws sqs receive-message --queue-url $saveurl

#creating s3 bucket:
aws s3 mb s3://$1 --region us-west-2
aws s3 mb s3://$2 --region us-west-2
