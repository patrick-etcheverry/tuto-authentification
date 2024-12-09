-- Mise à jour de la base de données pour la version 02
-- Objectif : Protection des mots de passe avec des fonctions de hachage.

-- Suppression et recréation de la table `utilisateurs`
DROP TABLE IF EXISTS utilisateurs;

CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,   -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,           -- Adresse email de l'utilisateur (doit être unique)
    password VARCHAR(255) NOT NULL                -- Mot de passe haché de l'utilisateur
);

-- Représentation des champs de la table après mise à jour
-- ┌─────────────┬───────────────────────────────────────────────────┐
-- │ Champ       │ Description                                        │
-- ├─────────────┼───────────────────────────────────────────────────┤
-- │ identifiant │ Identifiant unique, clé primaire, auto-incrémenté  │
-- │ email       │ Adresse email unique de l'utilisateur             │
-- │ password    │ Mot de passe haché de l'utilisateur               │
-- └─────────────┴───────────────────────────────────────────────────┘
