-- Réinitialisation de la table utilisateurs pour la version 03
-- Cette version introduit un champ salt pour le salage manuel des mots de passe.

-- Suppression de la table existante si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 03
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,         -- Adresse email de l'utilisateur
    salt VARCHAR(255) NOT NULL,                 -- Sel unique pour chaque utilisateur
    password VARCHAR(255) NOT NULL              -- Mot de passe haché avec le sel
);

-- Historique des modifications pour la version 03 :
-- - Ajout du champ salt : utilisé pour saler les mots de passe avant de les hacher.

-- Représentation textuelle des colonnes de la table utilisateurs :
-- +--------------+--------------+--------------------------------------------+
-- | Colonne      | Type         | Description                                |
-- +--------------+--------------+--------------------------------------------+
-- | identifiant  | INT          | Identifiant unique de l'utilisateur        |
-- | email        | VARCHAR(255) | Adresse email de l'utilisateur             |
-- | salt         | VARCHAR(255) | Sel unique pour chaque utilisateur         |
-- | password     | VARCHAR(255) | Mot de passe haché avec le sel             |
-- +--------------+--------------+--------------------------------------------+
