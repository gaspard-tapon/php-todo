# PHP Todo App

Application todo simple en PHP + MySQL, conçue comme tutoriel pour apprendre les bases de la mise en production et du CI/CD avec Docker et Dockploy.

## Stack

- PHP 8.3 (Apache)
- MySQL 8.0
- PhpMyAdmin

## Lancer en dev

```bash
cp .env.example .env
docker compose up --build -d
docker compose watch
```

- App : http://localhost:8080
- PhpMyAdmin : http://localhost:8888

## Structure

```
.
├── Dockerfile
├── compose.yml          # Dev (avec docker watch)
├── compose.prod.yml     # Prod (pour Dockploy)
├── init.sql             # Schema de la base
├── src/
│   └── index.php
├── .env.example
└── .env                 # Ignoré par git
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
