Les fichiers complémentaires se situe dans le dossier "garageautofichiercomplementaire" à la racine du projet 

lien Heroku: https://git.heroku.com/lit-atoll-46537.git

lien Trello: https://trello.com/b/I8hvJ3QS/mon-tableau-trello

Ce document fournit un aperçu des principales fonctionnalités de l'application web et explique la démarche à suivre pour son exécution en local.

Instructions d'Installation
Suivez ces étapes pour installer et exécuter l'application en local sur votre machine :

Cloner le dépôt sur votre machine.
Installer les dépendances avec Composer.
Configurer la base de données dans le fichier .env.
Créer la base de données et exécuter les migrations.
Créer un compte administrateur en utilisant la commande php bin/console app:create-admin.
Démarrer le serveur de développement avec la commande symfony server:start.
Fonctionnalités Principales
Se Connecter
Types d'utilisateurs : Administrateur, Employés
Création d'un compte administrateur réservé à Vincent Parrot, le chef d’entreprise.
Les employés peuvent se connecter à l'application à l'aide d'une adresse e-mail et d'un mot de passe sécurisé.
Présenter les Services
Affichage des différents services de réparation automobile sur la page d'accueil.
Possibilité pour l'administrateur de modifier ces informations depuis son espace d'administration.
Définir les Horaires d’Ouverture
Consultation des horaires d'ouverture du garage sur le site web.
Possibilité pour l'administrateur de spécifier les horaires d'ouverture depuis son espace dédié.
Exposer les Voitures d'Occasion
Présentation des véhicules d'occasion disponibles à la vente avec photos, descriptions détaillées et informations techniques.
Ajout de nouvelles voitures par les employés depuis leur espace dédié.
Filtrer la Liste des Véhicules d’Occasion
Mise en place d'un système de filtres pour faciliter la recherche de véhicules par les visiteurs.
Permettre de Contacter l'Atelier
Facilité de contact avec le garage par téléphone ou via un formulaire de contact en ligne.
Affichage des informations de contact en bas de chaque annonce de véhicule d’occasion.
Recueillir les Témoignages des Clients
Section dédiée sur la page d'accueil pour laisser un témoignage composé d'un nom, d'un commentaire et d'une note.
Modération des avis par un employé du garage avant affichage sur la page d’accueil.
Possibilité pour les employés d'ajouter des témoignages clients depuis leur espace dédié.
