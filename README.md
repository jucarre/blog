[![SymfonyInsight](https://insight.symfony.com/projects/73b79d08-0db1-45c2-af8d-5f360ffdb83b/mini.svg)](https://insight.symfony.com/projects/73b79d08-0db1-45c2-af8d-5f360ffdb83b) <img src="https://scrutinizer-ci.com/g/jucarre/blog/badges/quality-score.png?b=branche-V2" alt="Scrutinizer Code Quality" />  <img src="https://scrutinizer-ci.com/g/jucarre/blog/badges/code-intelligence.svg?b=branche-V2" alt="Code Intelligence Status" /> <img src="https://scrutinizer-ci.com/g/jucarre/blog/badges/build.png?b=branche-V2" alt="Build Status" />
# Blog

## Pré-requis : 

* PHP 7, MySQL, Apache.

## Installation:

* Télécharger composer, copier coller ses liens si dessous a la racine du projet, dans votre termial shell.
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

* Télécharger twig avec composer, exemple sur Mac à la racine du dossier: "php composer.phar install".
* Copier le contenu du fichier "/BDD.sql" vers votre base créé auparavant (dbname:"Blog" chez moi).
* Configurer la connexion à la base de donnée dans le fichier "/src/Model/Manager.php".
* L'adresse du serveur doit pointer à la racine du dossier.
