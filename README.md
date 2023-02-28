# Créez votre premier blog en PHP

[![SymfonyInsight](https://insight.symfony.com/projects/f4fd9de2-a556-486e-a630-f3b0d571f414/big.svg)](https://insight.symfony.com/projects/f4fd9de2-a556-486e-a630-f3b0d571f414)

## [🛠️] NGINX Configuration
Add the following configuration to your nginx server
```cmd
location / {
    try_files $uri $uri/ /index.php$is_args$args;
}
```

## [🛠️] Apache2 Configuration
Create a .htaccess file in the public directory and insert the following code
```cmd
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

## [🛠️] Installation
```cmd
git clone https://github.com/stevenoyer/P5-Blog-Openclassrooms.git
composer install
```

## [🛠️] Configuration
To configure the project, please modify the information in the configuration.php file

## [🛠️] Database SQL
Import the db.sql file for the project to work

## [🛠️] Admin account
```cmd
e-mail : admin@admin.fr
password : admin
```