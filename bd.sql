-- Réinitialisation de la table utilisateurs pour la version 11
-- Cette version introduit la protection des fonctionnalités basées sur les rôles utilisateurs.

-- Suppression de la table existante si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 11
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,          -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,                  -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL,                      -- Mot de passe haché
    tentatives_echouees INT DEFAULT 0 NOT NULL,          -- Nombre de tentatives échouées
    date_dernier_echec_connexion DATETIME DEFAULT NULL,  -- Date et heure du dernier échec de connexion
    statut_compte ENUM('actif', 'desactive') DEFAULT 'actif', -- Statut du compte
    role ENUM('admin', 'user') DEFAULT 'user'            -- Rôle de l'utilisateur (par défaut user)
);

-- Historique des modifications pour la version 11 :
-- - Mise à jour des fonctionnalités pour protéger les actions accessibles uniquement aux administrateurs.

-- Représentation textuelle des colonnes de la table utilisateurs :
-- +---------------------------+---------------------+------------------------------------------+
-- | Colonne                   | Type                | Description                              |
-- +---------------------------+---------------------+------------------------------------------+
-- | identifiant               | INT                 | Identifiant unique de l'utilisateur      |
-- | email                     | VARCHAR(255)        | Adresse email de l'utilisateur           |
-- | password                  | VARCHAR(255)        | Mot de passe haché                       |
-- | tentatives_echouees       | INT                 | Nombre de tentatives échouées            |
-- | date_dernier_echec_connexion | DATETIME         | Date et heure du dernier échec           |
-- | statut_compte             | ENUM('actif', 'desactive') | Statut du compte                  |
-- | role                      | ENUM('admin', 'user') | Rôle de l'utilisateur (user par défaut)|
-- +---------------------------+---------------------+------------------------------------------+
