# jagnawaterworkssystem-api


## Installing

1.) Create a database with name "jagnawaterworks";
2.) ~ composer install
3.) ~ cp .env.example .env
3.) Set your .env file (username, password, db name and etc);
4.) ~ php artisan migrate


## Run Project
to run this project
run at the project's root directory
=================================
~ php -S localhost:8000 -t public
=================================


## Notes && Require for login
Download https://www.getpostman.com/ 
1. POST for request
2. past the url (yourip/userApi/users)
3. For the headers key=content-type,value=application/x-www-form-urlencoded
5. For the body input key username="yourusername",password="yourpassword,userlevel="admin"

## For the front end
Link https://github.com/vlade2008/jagnawaterworksystem-react
