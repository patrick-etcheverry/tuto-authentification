-- Réinitialisation de la table utilisateurs pour la version 06
-- Cette version introduit un contrôle de robustesse pour les mots de passe.

-- Suppression de la table existante si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 06
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,         -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL              -- Mot de passe haché
);

-- Historique des modifications pour la version 06 :
-- - Aucune modification structurelle de la table par rapport à la version 05.
-- - L'ajout de la robustesse des mots de passe est une amélioration logicielle, non structurelle.

-- Représentation textuelle des colonnes de la table utilisateurs :
-- +--------------+--------------+--------------------------------------------+
-- | Colonne      | Type         | Description                                |
-- +--------------+--------------+--------------------------------------------+
-- | identifiant  | INT          | Identifiant unique de l'utilisateur        |
-- | email        | VARCHAR(255) | Adresse email de l'utilisateur             |
-- | password     | VARCHAR(255) | Mot de passe haché                         |
-- +--------------+--------------+--------------------------------------------+
