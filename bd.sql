-- Réinitialisation de la table utilisateurs pour la version 05
-- Cette version ajoute un contrôle d'unicité lors de l'inscription, mais ne modifie pas la structure de la table.

-- Suppression de la table existante si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 05
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,         -- Adresse email unique de l'utilisateur
    password VARCHAR(255) NOT NULL              -- Mot de passe haché avec salage automatique
);

-- Historique des modifications pour la version 05 :
-- - Aucun champ ajouté ou modifié, mais l'unicité de l'utilisateur est désormais gérée dans le code PHP.

-- Représentation textuelle des colonnes de la table utilisateurs :
-- +--------------+--------------+---------------------------------------------+
-- | Colonne      | Type         | Description                                 |
-- +--------------+--------------+---------------------------------------------+
-- | identifiant  | INT          | Identifiant unique de l'utilisateur         |
-- | email        | VARCHAR(255) | Adresse email unique de l'utilisateur       |
-- | password     | VARCHAR(255) | Mot de passe haché avec salage automatique  |
-- +--------------+--------------+---------------------------------------------+
