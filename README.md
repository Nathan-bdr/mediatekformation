# Mediatekformation

## Lien vers le dépôt d'origine
Le dépôt d'origine est disponible à l'adresse suivante : [mediatekformation](https://github.com/CNED-SLAM/mediatekformation)<br>
Il contient dans son readme la présentation de l'application d'origine.

## Présentation
Ce dépôt contient les évolutions apportées à l'application mediatekformation, développée avec Symfony 6.4.
Les fonctionnalités ajoutées concernent le front office et le développement complet d'un back office permettant de gérer le contenu de la base de données.

## Fonctionnalités ajoutées

### Front office
**Nombre de formations par playlist**<br>
Dans la page des playlists, une nouvelle colonne affiche le nombre de formations contenues dans chaque playlist, avec la possibilité de trier cette colonne en ordre croissant ou décroissant.<br>
Cette information est également affichée dans la page de détail d'une playlist.

<img width="1292" height="779" alt="Capture d&#39;écran 2026-03-27 150034" src="https://github.com/user-attachments/assets/722b6a04-f205-4496-a8d6-1c7fe308d915" />

### Back office
Le back office est accessible en ajoutant "/admin" à l'URL du site. L'accès est sécurisé par un formulaire d'authentification.

**Gestion des formations**<br>
Il est possible d'ajouter, modifier et supprimer une formation. Les mêmes tris et filtres que dans le front office sont disponibles. 

<img width="1294" height="775" alt="Capture d&#39;écran 2026-03-27 150148" src="https://github.com/user-attachments/assets/6a2af547-0c43-42eb-af18-93b832411ec0" /><br>

Le formulaire d'ajout et de modification permet de saisir le titre, la description, l'identifiant de la vidéo YouTube, la date de parution (ne pouvant pas être postérieure à aujourd'hui), la playlist et les catégories associées.

<img width="1301" height="1034" alt="Capture d&#39;écran 2026-03-27 150218" src="https://github.com/user-attachments/assets/82186f95-280e-4ced-bfdb-2614f47c9e18" /><br><br>

**Gestion des playlists**<br>
Il est possible d'ajouter, modifier et supprimer une playlist. La suppression n'est possible que si aucune formation n'est rattachée à la playlist. 

<img width="1291" height="775" alt="Capture d&#39;écran 2026-03-27 150242" src="https://github.com/user-attachments/assets/719c6cd3-7166-49b4-8e68-3385d4fc3a98" /><br>

Le formulaire de modification affiche la liste des formations rattachées en lecture seule.

<img width="1297" height="992" alt="Capture d&#39;écran 2026-03-27 150317" src="https://github.com/user-attachments/assets/9791c0bd-d48e-47b6-9b13-9db715fc76d1" /><br><br>


**Gestion des catégories**<br>
Il est possible d'ajouter et supprimer une catégorie directement depuis la page de liste. La suppression n'est possible que si aucune formation n'est rattachée à la catégorie.

<img width="1293" height="909" alt="Capture d&#39;écran 2026-03-27 150347" src="https://github.com/user-attachments/assets/96482062-3c22-4541-be47-090e7ffb0bbd" /><br><br>

**Authentification**<br>
L'accès au back office est sécurisé par un formulaire de connexion. Un lien de déconnexion est disponible sur toutes les pages du site.

<img width="1310" height="303" alt="Capture d&#39;écran 2026-03-27 150408" src="https://github.com/user-attachments/assets/0bb7f95b-225b-40ab-8bae-db867ceca582" />

## Installation et utilisation en local

### Prérequis
Vérifier que Composer, Git et Wampserver (ou équivalent) sont installés sur l'ordinateur.

### Installation
- Cloner le dépôt dans le dossier www de Wampserver et renommer le dossier en "mediatekformation".
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper :
```
composer install
```
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD "mediatekformation".
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD.
- Créer le fichier ".env" en racine du projet (en s'inspirant du fichier ".env.example" si présent) et renseigner la variable DATABASE_URL avec les informations de connexion à la BDD locale.
- Pour créer le compte administrateur, ouvrir une fenêtre de commandes dans le dossier du projet et taper :
```
php bin/console doctrine:fixtures:load --append --group=UserFixture
```
- Lancer l'application à l'adresse : http://localhost/mediatekformation/public/index.php

### Accès au back office en local
L'accès au back office se fait en ajoutant "/admin" à l'URL. Les identifiants de connexion sont fournis séparément dans la fiche rendue au formateur.

## Utilisation en ligne
L'application est accessible en ligne à l'adresse suivante :<br>
https://mediatekformation.nathan-boudier.com

La documentation technique est accessible à l'adresse suivante :<br>
https://mediatekformation.nathan-boudier.com/doc/index.html

### Accès au back office en ligne
L'accès au back office se fait en ajoutant "/admin" à l'URL. Les identifiants de connexion sont fournis séparément dans la fiche rendue au formateur.
