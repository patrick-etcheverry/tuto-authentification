# Inscription et authentification d'utilisateurs en PHP

## A propos

Ce dépôt a été conçu pour accompagner un atelier pédagogique sur la mise en place d'un système d'inscription et d'authentification sécurisé en PHP. 
Il met en lumière les concepts fondamentaux, les bonnes pratiques et les failles potentielles à éviter.

Les ressources présentes dans ce dépôt viennent en supports de travaux pratiques réalisés en séance, l'objectif de ces séances étant  :
- de découvrir les bases de l'inscription et de l'authentification des utilisateurs ;
- d'identifier et corriger les faiblesses des implémentations naïves ;
- de progresser vers des solutions de plus en plus robustes.


Pour mettre en avant les points essentiels des processus d'inscription et d'authentification, cet atelier adopte une approche progressive. Il commence par une version "naïve" et volontairement vulnérable, mettant en lumière un ensemble de défauts souvent rencontrés dans des implémentations de base. À partir de cette première version, chaque itération s'efforce de corriger l'un des défauts identifiés, tout en introduisant progressivement des concepts et des bonnes pratiques liés à la sécurisation de ces processus.

L'objectif est double :
- Identifier les risques associés à des implémentations insuffisamment sécurisées.
- Montrer les solutions adaptées pour renforcer la robustesse des systèmes d'authentification et d'inscription.

Chaque version de code met ainsi en évidence un point clé à considérer dans le cadre de l'authentification des utilisateurs, en expliquant comment le traiter.

Il est important de noter que les ressources disponibles dans ce dépôt n'ont pas la prétention de couvrir tous les aspects de la sécurité informatique. Elles se concentrent exclusivement sur le processus d'authentification, sans aborder d'autres dimensions essentielles comme par exemple la sécurisation des communications réseau.


## Structure des fichiers

Les fichiers proposés dans le code de départ sont les suivants  :

### 1. Fichiers principaux
- **`inscription.html`** : Formulaire d'inscription où l'utilisateur entre son email et son mot de passe.
- **`inscription.php`** : Script qui traite l'inscription en enregistrant les informations dans la base de données.
- **`authentification.html`** : Formulaire d'authentification où l'utilisateur entre ses identifiants pour se connecter.
- **`authentification.php`** : Script qui vérifie les informations saisies par l'utilisateur et permet ou refuse la connexion.

### 2. Fichiers utilitaires
- **`utilisateur.class.php`** : Classe PHP représentant un utilisateur. Elle contient les méthodes pour l'inscription et l'authentification.
- **`bd.class.php`** : Classe gérant la connexion à la base de données (implémentation du design pattern Singleton).
- **`config_bd.php`** : Fichier de configuration contenant les paramètres de connexion à la base de données et le nom de la table utilisateur.
- **`bd.sql`** : Script SQL pour créer la table des utilisateurs


## Installation et configuration du code de départ

Pour démarrer l'atelier, suivez ces étapes pour installer et configurer le code de départ :

1.  **Cloner le dépôt**  : utilisez le commande suivante pour cloner le dépôt sur votre environnement local :
```
git clone https://github.com/patrick-etcheverry/tuto-authentification.git
```

2.  **Configurer la base de données** : Créez ou utilisez une base de données existante sur votre serveur MySQL ou MariaDB puis importez le fichier bd.sql pour créer la table utilisateurs de départ.

3.  **Adapter les paramètres de connexion**  : Modifiez le fichier `config_bd.php` pour y intégrer les paramètres de connexion à votre base de données (hôte, nom de la base, utilisateur, mot de passe).

3.  **Tester le code**  : Accédez au formulaire d'inscription via votre navigateur (inscription.html) et vérifiez que vous arrivez à inscrire un utilisateur. Utilisez ensuite le formulaire d'authentification (authentification.html) pour vérifier que vous arrivez à vous authentifier avec l'utilisateur que vous avez précédemment inscrit en base de données.


## Mise à disposition et utilisation des différentes versions

Les différentes versions de cet atelier seront mises à disposition progressivement au cours de l'atelier, sous forme de branches distinctes dans ce dépôt. Chaque version est indépendante des autres et implémente une fonctionnalité ou une amélioration spécifique dans le processus d'inscription et d'authentification.

Chaque version utilise sa propre table utilisateurs, dont la structure peut évoluer d'une version à l'autre pour s'adapter aux besoins des nouvelles fonctionnalités. Le fichier bd.sql, fourni avec chaque version, contient le code SQL nécessaire pour créer la table utilisateurs associée.

