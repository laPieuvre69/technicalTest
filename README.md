# Backend API - Documentation

## Description

API Laravel utilisant Sanctum pour l'authentification et la gestion des profils.

## Configuration

### Modèles et Migrations

Deux modèles principaux sont utilisés :

- Administrateur (email, password)
- Profil (nom, prénom, image, statut)

### Routes API

Routes protégées (nécessitent authentification) :

- POST /profils - Création d'un profil
- PUT /profils/{id} - Mise à jour d'un profil
- DELETE /profils/{id} - Suppression d'un profil

Routes publiques :

- GET /profils - Liste des profils
- POST /sanctum/token - Authentification

## Authentification

L'authentification utilise Laravel Sanctum avec des tokens Bearer.

### Exemple d'utilisation

1. Obtention du token :

```bash
POST http://127.0.0.1:8001/api/sanctum/token
Body: {
    "email": "admin@example.fr",
    "password": "password"
}
```

1. Utilisation du token :

Ajouter dans les headers : Authorization: Bearer MY_SECRET_TOKEN

## Validation

La validation des données est gérée via des Request classes dédiées :

- StoreProfilRequest
- UpdateProfilRequest

## Seeding

Un seeder est disponible pour créer un compte administrateur initial.

## Temps de développement

Temps total de mise en place : 2 heures

## Notes techniques

- Utilisation de l'IA pour la génération des CRUD basiques
- Architecture RESTful
- Sécurisation des routes via middleware Sanctum
