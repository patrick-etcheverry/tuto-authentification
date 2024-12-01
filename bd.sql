-- Création de la table pour la version 00
CREATE TABLE IF NOT EXISTS users_version00 (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,   -- Identifiant de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,           -- Adresse email de l'utilisateur (doit être unique)
    password VARCHAR(255) NOT NULL                -- Mot de passe de l'utilisateur
);
