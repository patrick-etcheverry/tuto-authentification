-- Réinitialisation de la table utilisateurs pour la version 09
-- Cette version introduit la gestion des sessions pour garder l'utilisateur connecté.

-- Suppression de la table existante si elle existe déjà
DROP TABLE IF EXISTS utilisateurs;

-- Création de la table utilisateurs pour la version 09
CREATE TABLE utilisateurs (
    identifiant INT AUTO_INCREMENT PRIMARY KEY,          -- Identifiant unique de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,                  -- Adresse email de l'utilisateur
    password VARCHAR(255) NOT NULL,                      -- Mot de passe haché
    tentatives_echouees INT DEFAULT 0 NOT NULL,          -- Nombre de tentatives échouées
    date_dernier_echec_connexion DATETIME DEFAULT NULL,  -- Date et heure du dernier échec de connexion
    statut_compte ENUM('actif', 'desactive') DEFAULT 'actif' -- Statut du compte
);

-- Historique des modifications pour la version 09 :
-- - Ajout de la gestion des sessions pour garder l'utilisateur connecté après authentification.
-- - Mise en session des informations essentielles de l'utilisateur (`id` et `email`).
-- - Ajout d'une vérification dans `admin.php` pour limiter l'accès aux utilisateurs connectés.
-- - Aucune modification de la structure de la base de données.

-- Représentation textuelle des colonnes de la table utilisateurs :
-- +---------------------------+---------------------+------------------------------------------+
-- | Colonne                   | Type                | Description                              |
-- +---------------------------+---------------------+------------------------------------------+
-- | identifiant               | INT                 | Identifiant unique de l'utilisateur      |
-- | email                     | VARCHAR(255)        | Adresse email de l'utilisateur           |
-- | password                  | VARCHAR(255)        | Mot de passe haché                       |
-- | tentatives_echouees       | INT                 | Nombre de tentatives échouées            |
-- | date_dernier_echec_connexion | DATETIME         | Date et heure du dernier échec           |
-- | statut_compte             | ENUM('actif', 'desactive') | Statut du compte                    |
-- +---------------------------+---------------------+------------------------------------------+
