-- Réinitialisation de la table utilisateurs pour la version 04
-- Cette version utilise `password_hash()` pour hacher les mots de passe de manière sécurisée.
-- Les champs `salt` sont donc supprimés, car le salage est géré automatiquement par `password_hash()`.

-- Suppression de la table existante si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 04
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,         -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL              -- Mot de passe haché avec `password_hash()`
);

-- Historique des modifications pour la version 04 :
-- - Suppression du champ salt : désormais inutile grâce à l'utilisation de `password_hash()`.

-- Représentation textuelle des colonnes de la table utilisateurs :
-- +--------------+--------------+--------------------------------------------+
-- | Colonne      | Type         | Description                                |
-- +--------------+--------------+--------------------------------------------+
-- | identifiant  | INT          | Identifiant unique de l'utilisateur        |
-- | email        | VARCHAR(255) | Adresse email de l'utilisateur             |
-- | password     | VARCHAR(255) | Mot de passe haché avec `password_hash()`  |
-- +--------------+--------------+--------------------------------------------+
