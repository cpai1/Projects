#!/bin/bash

#load balancer name
lbname=`aws elb describe-load-balancers --query 'LoadBalancerDescriptions[*].{LbName:LoadBalancerName}'`
echo  $lbname

#auto scaling group
asgname=`aws autoscaling describe-auto-scaling-groups --query 'AutoScalingGroups[*].{asgName:AutoScalingGroupName}'`
echo $asgname

#lauch Configuration
asglcname=`aws autoscaling describe-launch-configurations --query 'LaunchConfigurations[*].{asgLcName:LaunchConfigurationName}'`
echo $asglcname

#detaching the load balancer
aws autoscaling detach-load-balancers --load-balancer-names $lbname --auto-scaling-group-name $asgname

#deleting the auto scaling group
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $asgname --force-delete

#delete launch configuration
aws autoscaling delete-launch-configuration --launch-configuration-name $asglcname

sleep 30

#Retrieving InstanceId
runInstanceID=`aws ec2 describe-instances --query 'Reservations[*].Instances[*].[State.Name, InstanceId]' --output text | grep running | awk '{print $2}'`
echo $runInstanceID

#deregister the instances
aws elb deregister-instances-from-load-balancer --load-balancer-name $lbname --instances $runInstanceID

#deleting the load balancers
aws elb delete-load-balancer-listeners --load-balancer-name $lbname --load-balancer-ports 80

aws elb delete-load-balancer --load-balancer-name $lbname

#terminating the instances
aws ec2 terminate-instances --instance-ids $runInstanceID