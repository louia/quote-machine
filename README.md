
<h1 align="center">Welcome to quote-machine 👋</h1>
<p>
</p>

> La Quote Machine est composé de fonctionnalités diverses et variées comme par exemple un CRUD de quotes, l'affichage d'une quote aléatoire, un système de catégorie, un système de droit et des imports en ligne de commande

### 🏠 [Homepage](/)

## Install

```sh
composer install
```

## Usage
Configurer vos identifiants de base de données dans le fichier de configuration .env comme suit :
```env
DATABASE_URL="mysql://user_bd:pwd_bd@127.0.0.1:3306/name_bd"
```
<br>
Créer la base de donnée avec la commande :

```sh
php bin/console doctrine:database:create
```
<br>
Installer la base de donnée avec la commande :

```sh
php bin/console doctrine:migrations:migrate
```
<br>
Nourrir la base de donnée avec la commande :

```sh
php bin/console doctrine:fixtures:load
```
<br>
Installer webpackEncore :

```sh
npm install
npm run dev
```
<br>

Lancer le serveur à l'aide de la commande :
```sh
php bin/console server:start
```

Aller à l'adresse : [http://127.0.0.1:8000](http://127.0.0.1:8000/)

## Author

👤 **Louis Chovaneck**

* Gitlab: [@chov0001](https://iut-info.univ-reims.fr/gitlab/chov0001)



***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_