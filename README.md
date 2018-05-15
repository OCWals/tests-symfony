# Openclassroom : Tests fonctionnels avec PHPUnit et Symfony

A Symfony project created on 2017-09-11

Cours OpenClassroom sur les tests avec Symfony 

https://openclassrooms.com/courses/testez-fonctionnellement-votre-application-symfony

### ATTENTION :
Ne pas oublier de modifier le fichier parameters.yml pour indiquer la bonne BDD et les bons identifiants d'accès !




food-diary
==========

 
Installation
============
 
First of all you need to run the `$ composer install`.
 
 This application requires a MySQL database. You need to configure the following parameters in the app/config/parameters.yml
 file :
 
     database_host
     database_port
     database_name
     database_user
     database_password
Then run the following commands in your favorite command line tool :

`$ bin/console doctrine:database:create`
`$ bin/console doctrine:schema:create`
