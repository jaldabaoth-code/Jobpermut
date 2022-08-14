<h1>Jobpermut (Project 3, WCS Web PHP)</h1>

### Create a site for a real client by using Symfony


---

![Jobpermut](https://i.ibb.co/HNm1snS/logo-marque-horizontal-resized.png)

## Jobpermut

### index

1. [Description](#Description)
2. [Prerequisites](#Prerequisites)
3. [Users](#Users)
4. [Installation](#Installation)
5. [Built-With](#Built-With)
6. [Authors](#Authors)

### Description

Jobpermut is a job exchange site. Unregistered users can simulate their commute time and users must register in order to access the service.
Once registered and logged in, they can compare their home-work distance with the values of other users.
If a route interests them, they can "match" in order to offer the job exchange. 

### Prerequisites

* [PHP 7.4.*](https://www.php.net/releases/7_4_0.php) (check by running php -v in your console)
* [Composer 2.*](https://getcomposer.org/) (check by running composer --version in your console)
* [node 14.*](https://nodejs.org/en/) (check by running node -v in your console)
* [Yarn 1.*](https://yarnpkg.com/) (check by running yarn -v in your console)
* [MySQL 8.0.*](https://www.mysql.com/fr/) (check by running mysql --version in your console)
* [Git 2.*](https://git-scm.com/) (check by running git --version in your console)
* You will also need a test SMTP connection, which you can configure using tools like Mailtrap
* WARNING : This app use [Open Route Service](https://openrouteservice.org/) API, you also need to get an API key from this service
* WARNING : This app use [Pole emploi ROMEv1](https://pole-emploi.io/data/api/rome) API, you also need to get an API key from this service

### Steps

If you meet the prerequisites, you can proceed to the installation of the project 

1. Clone the repo from GitHub : `git clone git@github.com:jaldabaoth-code/Jobpermut.git`
2. Enter the directory : `cd Jobpermut`
3. Open with your code editor
4. Run `composer install` to install PHP dependencies
5. Run `yarn install` to install JS dependencies
6. Copy the `.env` file and fill all informations (Database, Symfony/Mailer, Open Route Service, Pole Emploi Variable)
7. Run `symfony console doctrine:database:create` to create database
8. Run `symfony console doctrine:migration:migrate` to create structure of database
9. Run `symfony console doctrine:fixtures:load` to load the fixtures in database
10. Run `yarn encore dev` to build assets
11. Run `symfony server:start` to launch symfony server
12. Go to <b>localhost:8000</b> with your favorite browser

### Users

Demo User<br/>
login: john@doe.com<br/>
password: 123456789

Admin user<br/>
login: wildjobexchangeAdmin@gmail.com<br/>
password: admin123456789

SuperAdmin User<br/>
login: wildjobexchangeSuperAdmin@gmail.com<br/>
password: admin123456789

### Built-With

* [Symfony](https://github.com/symfony/symfony)
* [GrumPHP](https://github.com/phpro/grumphp)
* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHPStan](https://github.com/phpstan/phpstan)
* [PHPMD](http://phpmd.org)
* [ESLint](https://eslint.org/)
* [Sass-Lint](https://github.com/sasstools/sass-lint)

### Authors

* [Mael Chariault](https://github.com/bouboumael)
* [Zurabi Grialat](https://github.com/jaldabaoth-code)
* [Lochlainn Gadault](https://github.com/wonecode)
* [Raphaël Billet Servoin](https://github.com/RaphaelBS-WCS)
* [Tennessee Houry](https://github.com/RedPandore)

---

## The Links

<a href="https://github.com/WildCodeSchool/orleans-202103-php-project-jobpermut/tree/master">Link to the repository of project where we worked during <b>WCS Web Project 3</b></a>

<a href="http://jobpermut.fr/">Link to current website of <b>Jobpermut</b></a>
