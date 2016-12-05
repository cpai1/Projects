*Create-env.sh: 
*Loads all the instances and php files: index.php,welcome.php,logout.php,upload.php,uploader.php and admin.php.
*Please execute this command (positional parameters): sh ./create-env.sh ami-13882e73 chandu sg-8f6fa4f6 chandu-config developer chandu1990(pass the client token) 3 
(for ur Environment: change all the values and run the command)
*User Data : installapp.sh

Create-app-env-sh:
*Loads the RDS instances ,creates SNS,SQS topics and two S3 Buckets raw-cai, finished-cai.
*Please execute this command : sh ./create-app-env.sh raw-cai finished-cai
*Please do not change the bucket name as it has been hardcoded in few php files


*Destroy-env.sh:
*Destroys EC2 instances and RDS instances
*Every value is hardcoded. Therefore run this command : sh ./destroy-env.sh chandu1990(pass the same client token given in create-env.sh)

*init-pro-env.sh
*This file is created to process the crontab operation on edit.php file
*Loads an the instance and php file
*Please execute this command(positional parameters) : sh ./init-pro-env.sh ami-13882e73 chandu sg-8f6fa4f6 developer 1 
(for ur Environment: change all the values and run the command)
*User Data : initprocess.sh

For the index page:

*Admin Login
Username: cpai1@hawk.iit.edu
Password:admin 

*User Login
Username: hajek.iit.edu
Password:user 

*SQS name= chanduQueue
*SNS name= my-message


