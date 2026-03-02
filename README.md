# PHP Todo App

Application todo simple en PHP + MySQL, conçue comme tutoriel pour apprendre les bases de la mise en production et du CI/CD avec Docker et Dockploy.

## Stack

- PHP 8.3 (Apache)
- MySQL 8.0
- Composer (autoload PSR-4, PHPUnit)
- PhpMyAdmin (dev & pré-prod)
- Docker + Docker Compose

## Lancer en dev

```bash
cp .env.example .env
docker compose up --build -d
docker compose watch
```

- App : http://localhost:8080
- PhpMyAdmin : http://localhost:8888

## Tests

Les tests unitaires utilisent PHPUnit 11 avec SQLite en mémoire (pas besoin de MySQL).

```bash
# Installer les dépendances de test
composer install --working-dir=src

# Lancer les tests
src/vendor/bin/phpunit --configuration phpunit.xml
```

Les tests tournent automatiquement en CI (GitHub Actions et GitLab CI) sur push vers la branche `test`.

## Structure

```
.
├── Dockerfile              # Image PHP 8.3 Apache + Composer
├── compose.yml             # Dev (avec docker watch + PhpMyAdmin)
├── compose.preprod.yml     # Pré-prod (avec PhpMyAdmin, sans ports exposés)
├── compose.prod.yml        # Prod (sans PhpMyAdmin)
├── init.sql                # Schéma de la base (table todos)
├── phpunit.xml             # Configuration PHPUnit
├── src/
│   ├── composer.json       # Autoload PSR-4 + dépendances
│   ├── index.php           # Point d'entrée (routage POST/GET)
│   ├── classes/
│   │   ├── Database.php    # Connexion PDO MySQL
│   │   └── TodoRepository.php  # CRUD todos
│   └── templates/
│       ├── todos.php       # Template HTML
│       └── style.css       # Styles
├── tests/
│   ├── bootstrap.php       # Setup SQLite pour les tests
│   └── TodoRepositoryTest.php  # Tests unitaires du repository
├── .github/workflows/
│   └── tests.yml           # CI GitHub Actions
├── .gitlab-ci.yml          # CI GitLab
├── .env.example            # Variables d'environnement (template)
└── .env                    # Variables d'environnement (ignoré par git)
```

## Variables d'environnement

| Variable | Description |
|---|---|
| `DB_HOST` | Hôte de la base de données |
| `DB_NAME` | Nom de la base |
| `DB_USER` | Utilisateur MySQL |
| `DB_PASSWORD` | Mot de passe MySQL |
| `MYSQL_ROOT_PASSWORD` | Mot de passe root MySQL |
| `PMA_USER` | Utilisateur PhpMyAdmin |
| `PMA_PASSWORD` | Mot de passe PhpMyAdmin |

En dev, ces variables sont dans `.env`. En prod, elles sont configurées dans l'interface Dockploy.

## Déploiement (Dockploy)

1. Connecter le repo GitHub dans Dockploy
2. Sélectionner `compose.prod.yml` comme fichier compose
3. Configurer les variables d'environnement dans la section "Environment"
4. Déployer
