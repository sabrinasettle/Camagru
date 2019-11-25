# Camagru
The first project in the Web Branch of the 42 School Network. A sorta off-brand version of Instagram, built for sharing photos and adding fun stickers to them.

## Database setup

My Mysql database was containized in a Docker machine called CamaDB, it was called everytime I started work. 

It was setup with the commands

```bash
docker-machine create 'anyName'

eval $(docker-machine env 'anyName')

docker volume create --driver local --name=hatchery

docker run -d --restart=always -v hatchery:/var/lib/mysql --name=mysql_server -p 3306:3306 -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=ft_'CurrentUser' mysql --default-authentication-plugin=mysql_native_password

docker run -d --restart=always --name=php_server -p 8081:80 --link=mysql_server:mysql -e PMA_HOST=mysql_server phpmyadmin/phpmyadmin
```

From there its a case of setting it up in the config folder

## The config folder

From the docker machine info I specified we clearly make varibles of the important values
```php
$DB_HOST = '192.168.99.100:3306';
$DB_USER = 'root';
$DB_PASSWORD = 'password';
$DB_NAME = 'camagru';
```
from there the phpmyadmin page could accessed by going to:
```bash
192.168.99.100:8081
```

## The rest of the project

For emails I used the php built in email function to send all my emails. 



A great way to test emails is: https://www.mailinator.com/ 

<img width="703" alt="Screen Shot 2019-11-25 at 3 49 55 PM" src="https://user-images.githubusercontent.com/22520221/69587851-b046d100-0f9b-11ea-9474-0f7e5c6d863a.png">

