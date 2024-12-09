<?php

/***********************************
 PARAMETRES DE LA BASE DE DONNEES
 ***********************************/
define('DB_HOST', 'localhost'); // à adapter
define('DB_NAME', 'atelier_authentification'); // à adapter
define('DB_USER', 'patrick'); // à adapter
define('DB_PASS', 'patrick'); // à adapter

/***********************************
 PARAMETRES DE L'APPLICATION
 ***********************************/
// Nombre maximum de tentatives échouées avant désactivation du compte
define('MAX_CONNEXIONS_ECHOUEES', 3);

// Délai d'attente après désactivation (en secondes)
define('DELAI_ATTENTE_CONNEXION', 30 * 60); // 30 minutes
