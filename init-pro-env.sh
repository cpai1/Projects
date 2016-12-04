#!/bin/bash



echo "Enter the image ID : $1"
echo "Enter the key name : $2"
echo "Enter the security group ID : $3"
echo "How many Instances : $5"
echo "Enter the IAM role : $4"


scriptfile="file://initprocess.sh"
subnetID="subnet-2b93d25d"
instanceType="t2.micro"
Zone="us-west-2b"




#Load Instance

aws ec2 run-instances --image-id $1 --key-name $2 --security-group-ids $3 --instance-type $
iptfile --placement AvailabilityZone=us-west-2b --iam-instance-profile Name=$4 --count $5





crontab -l | { cat; echo "* * * * * php /var/www/html/edit.php >> /tmp/edit.log"; } | crontab -

