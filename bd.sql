-- Réinitialisation de la table utilisateurs pour la version 12
-- Cette version introduit la gestion de la réinitialisation de mot de passe.

DROP TABLE IF EXISTS utilisateurs;

CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,          -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,                  -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL,                      -- Mot de passe haché
    tentatives_echouees INT DEFAULT 0 NOT NULL,          -- Nombre de tentatives échouées
    date_dernier_echec_connexion DATETIME DEFAULT NULL,  -- Date et heure du dernier échec de connexion
    statut_compte ENUM('actif', 'desactive') DEFAULT 'actif', -- Statut du compte
    role ENUM('admin', 'user') DEFAULT 'user',           -- Rôle de l'utilisateur (par défaut user)
    token_reinitialisation VARCHAR(255) DEFAULT NULL,               -- Token unique pour réinitialisation
    expiration_token DATETIME DEFAULT NULL         -- Date d'expiration du token
);

-- Historique des modifications pour la version 12 :
-- - Ajout du champ `reset_token` : pour stocker un token unique de réinitialisation.
-- - Ajout du champ `reset_token_expiration` : pour gérer l'expiration du token (meilleure sécurité).

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
-- | token_reinitialisation   | VARCHAR(255)        | Token unique pour réinitialisation       |
-- | expiration_token         | DATETIME            | Expiration du token de réinitialisation  |
-- +---------------------------+---------------------+------------------------------------------+
