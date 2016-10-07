#!/bin/bash

scriptfile="file://installapp.sh"
securityID="sg-8f6fa4f6"
imageID="ami-06b94666"
subnetID="subnet-2b93d25d"
keyName="chandu"
instanceType="t2.micro"
Zone="us-west-2b"
instanceCount="3"
loadBalancerName="itmo-544-chandu"
autoLaunchConfig="chandu-config"
autogrpName="chandu-asg"

# please enter the client-token while executing the script file Example: sh ./create-env.sh chandra

#Load Instance

aws ec2 run-instances --image-id $imageID --key-name $keyName --security-group-ids $securityID --client-token $1 --instance-type $instanceType --user-data $scriptfile --placement AvailabilityZone=us-west-2b --count $instanceCount

getinstanceID=`aws ec2 describe-instances --filters "Name=client-token,Values=$1" --query 'Reservations[*].Instances[].InstanceId'`

echo  $getinstanceID

aws ec2 wait instance-running --instance-ids $getinstanceID

#Load Balancer

aws elb create-load-balancer --load-balancer-name $loadBalancerName --listeners "Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80" --subnets $subnetID --security-groups $securityID

aws elb register-instances-with-load-balancer --load-balancer-name $loadBalancerName --instances $getinstanceID

#Auto Scaling

aws autoscaling create-launch-configuration --launch-configuration-name $autoLaunchConfig --key-name $keyName --image-id $imageID --security-groups $securityID --instance-type $instanceType --user-data $scriptfile

aws autoscaling create-auto-scaling-group --auto-scaling-group-name $autogrpName --launch-configuration-name $autoLaunchConfig --availability-zone $Zone --load-balancer-names $loadBalancerName --max-size 5 --min-size 0 --desired-capacity 1

aws autoscaling attach-instances --instance-ids $getinstanceID  --auto-scaling-group-name $autogrpName

aws autoscaling attach-load-balancers --auto-scaling-group-name $autogrpName --load-balancer-names $loadBalancerName

