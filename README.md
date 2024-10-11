# Planteo Backend

## Description

Ce dépôt contient le code source du back-end de Planteo, un site web qui fournit des informations détaillées sur les semis, les plantations et les récoltes de plantes du potager. Le back-end gère également les fonctionnalités utilisateur, les plantes favorites, et la gestion des données personnelles.

## Table des Matières

- [Installation](#installation)
- [Usage](#usage)
- [Auteure](#auteure)

## Installation

### Prérequis

- [PHP](https://www.php.net/) (v7.3 ou supérieur)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)

### Étapes

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/Smophmie/planteo-backend.git
   cd planteo-backend

2. Installez les dépendances PHP avec Composer :
    ```bash
    composer install

3. Configurez le fichier .env :
    - Copiez le fichier .env.example en .env :
        ```bash
        cp .env.example .env
    - Ouvrez le fichier .env et configurez les variables d'environnement, en particulier celles concernant la connexion à la base de données :
        ```bash
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=nom_de_votre_base_de_donnees
        DB_USERNAME=votre_nom_utilisateur
        DB_PASSWORD=votre_mot_de_passe

4. Initialisez la base de données :
    ```bash
    php artisan migrate
    php artisan db:seed
    php artisan db:seed --class=PlantsTableSeeder

## Usage

Pour démarrer le serveur de développement Laravel, utilisez la commande suivante :

    ```bash
    php artisan serve

## Auteure

Sophie Théréau - Créatrice et mainteneuse principale - Smophmie
