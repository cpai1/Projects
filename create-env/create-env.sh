#!/bin/bash

if [ "$#" -eq 6 ]

then

echo "Enter the image ID : $1"
echo "Enter the key name : $2"
echo "Enter the security group ID : $3"
echo "How many Instances : $6"
echo "Enter the Auto scaling launch configuration name : $4"
echo "Enter the IAM role : $5"


scriptfile="file://installapp.sh"
subnetID="subnet-2b93d25d"
instanceType="t2.micro"
Zone="us-west-2b"
loadBalancerName="itmo-544-chandu"
autogrpName="chandu-asg"


#Load Instance

aws ec2 run-instances --image-id $1 --key-name $2 --security-group-ids $3 --instance-type $instanceType --user-data $scriptfile --placement AvailabilityZone=us-west-2b --iam-instance-profile Name=$5 --client-token $6 --count $7

sleep 25

getinstanceID=`aws ec2 describe-instances --filters "Name=client-token,Values=$6" --query 'Reservations[*].Instances[].[InstanceId]'`

echo  $getinstanceID

aws ec2 wait instance-running --instance-ids $getinstanceID

#Load Balancer

aws elb create-load-balancer --load-balancer-name $loadBalancerName --listeners "Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80" --subnets $subnetID --security-groups $3

aws elb register-instances-with-load-balancer --load-balancer-name $loadBalancerName --instances $getinstanceID

#Auto Scaling
aws autoscaling create-launch-configuration --launch-configuration-name $4 --key-name $2 --image-id $1 --security-groups $3 --instance-type $instanceType --user-data $scriptfile

aws autoscaling create-auto-scaling-group --auto-scaling-group-name $autogrpName --launch-configuration-name $4 --availability-zone $Zone --load-balancer-names $loadBalancerName --max-size 5 --min-size 0 --desired-capacity 1

aws autoscaling attach-load-balancers --auto-scaling-group-name $autogrpName --load-balancer-names $loadBalancerName

else
echo "Invalid arguments"
exit 1
fi
   



































































































