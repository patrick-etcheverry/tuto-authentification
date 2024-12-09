<?php

// Import des paramètre de la BD
require_once 'config_app.php';

/**
 * Classe BD
 *
 * Cette classe est responsable de fournir une instance unique (Singleton)
 * pour gérer la connexion à la base de données à travers PDO.
 *
 * Utilisation :
 * Pour obtenir une instance PDO, appeler BD::getInstancePdo().
 */
class BD
{
    /**
     * @var ?PDO Instance unique de PDO ou null si non encore initialisée.
     */
    private static ?PDO $instancePdo = null;

    /**
     * Retourne une instance PDO pour se connecter à la base de données.
     *
     * @return PDO Instance PDO pour la base de données.
     * @throws PDOException En cas d'échec de connexion.
     */
    public static function getInstancePdo(): PDO
    {
        if (self::$instancePdo === null)
        {
            try
            {
                // Création de l'instance PDO avec les paramètres de configuration de la BD
                self::$instancePdo = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                    DB_USER,
                    DB_PASS
                );

                /* Configure PDO pour lever une exception en cas d'erreur SQL.
                   Cela permet de détecter et de gérer les erreurs SQL via try-catch */
                self::$instancePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Définit le mode de récupération par défaut pour retourner des objets
                self::$instancePdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            }
            catch (PDOException $e)
            {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$instancePdo;
    }

    /**
     * Empêche l'instanciation directe ou le clonage de la classe.
     */
    private function __construct()
    {
    }
    private function __clone()
    {
    }
}
