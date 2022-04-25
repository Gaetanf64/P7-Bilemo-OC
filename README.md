# Projet 7 OpenClassRoom - Développeur d'application PHP/Symfony

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a15a65c3c4214fdfb0ae3cb298e3dd87)](https://www.codacy.com/gh/Gaetanf64/P7-Bilemo-OC/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Gaetanf64/P7-Bilemo-OC&amp;utm_campaign=Badge_Grade)

## Contexte

BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

## Besoin du client

Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

* consulter la liste des produits BileMo ;
* consulter les détails d’un produit BileMo ;
* consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
* consulter le détail d’un utilisateur inscrit lié à un client ;
* ajouter un nouvel utilisateur lié à un client ;
* supprimer un utilisateur ajouté par un client.

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

Vous avez le choix entre mettre en place un serveur OAuth et y faire appel (en utilisant le FOSOAuthServerBundle), et utiliser Facebook, Google ou LinkedIn. Si vous décidez d’utiliser JWT, il vous faudra vérifier la validité du token ; l’usage d’une librairie est autorisé.

## Contraintes

Le premier partenaire de BileMo est très exigeant : il requiert que vous exposiez vos données en suivant les règles des niveaux 1, 2 et 3 du modèle de Richardson. Il a demandé à ce que vous serviez les données en JSON. Si possible, le client souhaite que les réponses soient mises en cache afin d’optimiser les performances des requêtes en direction de l’API.

## Installation du projet 

* Cloner le projet avec gitclone 
```
https://github.com/Gaetanf64/P7-Bilemo-OC.git
```

* Installer les dépendances 
```
composer install
```

* Copier le fichier .env_sample et le renommer en .env
  
* Mettre à jour la base de données en entrant votre nom d'utilisateur et le mot de passe dans le .env_sample:
```
DATABASE_URL=mysql://votreusername:votrepassword@127.0.0.1:3306/bilemo
```

* Authentification avec jeton JWT, il faut créer et configurer le fichier:
```
mkdir config/jwt
```

```
openssl genrsa -out config/jwt/private.pem -aes256 4096
```

```
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Une PASSPHRASE est demandée dans .env, veuillez la renseigner:
```
JWT_PASSPHRASE=0b878c0eb5d40d613bec8fc614a2c12e
```

* Créer la base de données si elle n'existe pas déjà en entrant cette commande à la racine du projet : 
```
php bin/console doctrine:database:create
```

* Créer les tables du projet en appliquant les migrations : 
```
php bin/console make:migration
```

```
php bin/console doctrine:migrations:migrate
```

* Installer les DataFixtures (données initiales) : 
```
php bin/console doctrine:fixtures:load
```

* Pour utiliser l'API correctement, veuillez vous rendre dans DataFixtures pour connaître les usernames et password des Clients.

* Pour utiliser l'API avec swagger: 
```
http://localhost:8000/swagger/#/
```