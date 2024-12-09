-- Version 01 : Les mots de passe sont cryptés avec `base64_encode()`

-- Suppression de la table si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,   -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,           -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL                -- Mot de passe crypté de l'utilisateur
);

-- Évolution par rapport à la version précédente (Version 00) :
-- - Le champ `password` stocke désormais des mots de passe cryptés avec `base64_encode`.

-- Structure des champs après cette version :
-- +----------------+---------------+-------------------------------------+
-- | Champ          | Type          | Description                         |
-- +----------------+---------------+-------------------------------------+
-- | identifiant    | INT           | Identifiant unique de l'utilisateur |
-- | email          | VARCHAR(255)  | Adresse email de l'utilisateur      |
-- | password       | VARCHAR(255)  | Mot de passe crypté                 |
-- +----------------+---------------+-------------------------------------+
