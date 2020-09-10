
<h1 align="center">Welcome to quote-machine 👋</h1>
<p>
</p>

> La Quote Machine est composé de fonctionnalités diverses et variées comme par exemple un CRUD de quotes, l'affichage d'une quote aléatoire, un système de catégorie, un système de droit et des imports en ligne de commande et un système d'envoi de mail. 

> Ce projet a été réalisé durant mon année de licence WIMSI dans le cadre de ma formation à **Symfony 4**. Les tests unitaires et fonctionnels on été réalisés avec **PHPUnit**.

### 🏠 Homepage

## Install

```sh
composer install
npm install
npm run dev
```

## Usage
Configurer vos identifiants de base de données dans le fichier de configuration .env comme suit :
```env
DATABASE_URL="mysql://user_bd:pwd_bd@127.0.0.1:3306/name_bd"
```
<br>
Supprime, crée, exécute les migrations et charge les fixtures de votre base de données.

```env
composer db
```
<br>
Installer webpackEncore :

```sh
npm run dev
```
<br>
Lancer MailHog avec Docker :
 
```sh
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```
<hr>
<h4>Fixer le code</h4>

Sous linux :

```sh
composer cs
```
Sous windows : 
```sh
vendor\bin\php-cs-fixer fix
```
<hr>

Lancer le serveur à l'aide de la commande :
```sh
php bin/console server:start
```

Aller à l'adresse : [http://127.0.0.1:8000](http://127.0.0.1:8000/)

##Test

Supprime, crée et met à niveau le schéma de la base de données de test puis exécute les tests
```sh
composer test
```
## Author

👤 **Louis Chovaneck**




***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_
