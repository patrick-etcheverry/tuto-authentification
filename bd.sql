-- Supprime la table utilisateurs si elle existe, pour garantir une structure propre
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 00
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,   -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,           -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL                -- Mot de passe en clair de l'utilisateur
);

-- Représentation des champs de la table utilisateurs (version 00) :
/*
+-------------+---------------+---------+-------------------+
| Nom         | Type          | Clé     | Description       |
+-------------+---------------+---------+-------------------+
| identifiant | INT           | PRIMARY | Identifiant unique|
| email       | VARCHAR(255)  | UNIQUE  | Adresse email     |
| password    | VARCHAR(255)  |         | Mot de passe      |
+-------------+---------------+---------+-------------------+
*/
