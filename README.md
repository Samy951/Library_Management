# 📚 Library Management System

Système de gestion de bibliothèque développé avec Laravel 12 et PHP 8.2+. Application complète avec API REST, interface web moderne et documentation interactive.

## 🚀 Fonctionnalités

### 🔧 Backend & API
- **API REST complète** avec endpoints CRUD pour auteurs et livres
- **Sécurité API** - Lecture publique, écriture authentifiée
- **Documentation interactive Swagger/OpenAPI** accessible via l'interface web
- **Validation robuste** avec messages d'erreur en français
- **Recherche et filtrage avancés** (nom d'auteur, titre, prix, date)
- **Pagination et tri** sur tous les endpoints
- **Relations Eloquent** optimisées (One-to-Many)
- **Tests complets** (66 tests couvrant API et sécurité)

### 🎨 Frontend & Interface
- **Interface web moderne** avec Tailwind CSS et Alpine.js
- **Composants Livewire** pour une expérience utilisateur fluide
- **Dashboard avec statistiques** en temps réel
- **Formulaires interactifs** avec validation côté client
- **Recherche et tri en temps réel** sans rechargement de page
- **Design responsive** adapté mobile et desktop

### 📊 Gestion des données
- **Base de données SQLite** pour faciliter le développement
- **Seeders avec données réalistes** (littérature française)
- **Migrations structurées** avec contraintes d'intégrité
- **Soft deletes** et gestion des relations

## 🔗 Accès rapide

- **Application web** : [http://localhost:8000](http://localhost:8000)
- **Documentation API** : [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
- **API Base URL** : `http://localhost:8000/api`

## 📖 Documentation

- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Documentation complète de l'API avec exemples
- **Documentation Swagger** - Interface interactive pour tester l'API
- **Tests** - Exemples d'utilisation dans les fichiers de test

## 🛠️ Installation

```bash
# Cloner le projet
git clone <repository-url>
cd library-management

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Compiler les assets
npm run build

# Démarrer le serveur
php artisan serve
```

## 🧪 Tests

```bash
# Exécuter tous les tests
php artisan test

# Tests API uniquement
php artisan test --filter="AuthorApiTest|BookApiTest"

# Tests avec couverture
php artisan test --coverage
```

**Couverture actuelle** : 66 tests passants
- 16 tests AuthorApiTest (CRUD, validation, recherche, règles métier)
- 20 tests BookApiTest (CRUD, validation, filtres, tri, pagination)
- 10 tests ApiSecurityTest (authentification, autorisation)
- 20 tests Auth (authentification Laravel Breeze)

## 📋 Endpoints API principaux

### Auteurs
- `GET /api/authors` - Liste avec recherche et tri (🌐 public)
- `POST /api/authors` - Créer un auteur (🔒 auth requise)
- `GET /api/authors/{id}` - Détails d'un auteur (🌐 public)
- `PUT /api/authors/{id}` - Mettre à jour (🔒 auth requise)
- `DELETE /api/authors/{id}` - Supprimer si aucun livre (🔒 auth requise)

### Livres
- `GET /api/books` - Liste avec filtres avancés (🌐 public)
- `POST /api/books` - Créer un livre (🔒 auth requise)
- `GET /api/books/{id}` - Détails d'un livre (🌐 public)
- `PUT /api/books/{id}` - Mettre à jour (🔒 auth requise)
- `DELETE /api/books/{id}` - Supprimer (🔒 auth requise)

## 🔍 Exemples d'utilisation

### Recherche d'auteurs
```bash
curl "http://localhost:8000/api/authors?search=Victor&sort=last_name"
```

### Filtrage de livres par prix
```bash
curl "http://localhost:8000/api/books?min_price=10&max_price=20&sort=price"
```

### Création d'un auteur
```bash
curl -X POST "http://localhost:8000/api/authors" \
  -H "Content-Type: application/json" \
  -d '{"first_name":"Antoine","last_name":"de Saint-Exupéry"}'
```

## 🏗️ Architecture

### Stack technique
- **Backend** : Laravel 12, PHP 8.2+
- **Frontend** : Blade, Tailwind CSS, Vanilla JavaScript
- **Base de données** : SQLite
- **Documentation** : Swagger/OpenAPI (l5-swagger)
- **Tests** : PHPUnit, Feature Tests

### Structure du projet
```
app/
├── Http/Controllers/Api/     # Contrôleurs API REST
├── Http/Requests/           # Validation des requêtes
├── Http/Resources/          # Formatage JSON avec annotations OpenAPI
├── Livewire/               # Composants interface web
├── Models/                 # Modèles Eloquent
resources/views/            # Vues Blade et composants Livewire
tests/Feature/              # Tests d'intégration API
database/
├── migrations/             # Structure de base de données
├── seeders/               # Données de test
└── factories/             # Factories pour les tests
```

## ⚡ Fonctionnalités avancées

### Recherche intelligente
- Recherche dans les noms d'auteurs depuis l'API livres
- Recherche full-text dans titres et auteurs
- Filtres combinables (prix, auteur, date)

### Validation métier
- Un auteur ne peut être supprimé s'il a des livres
- Les dates de publication ne peuvent être dans le futur
- Prix limités entre 0 et 999.99€
- Messages d'erreur en français

### Performance
- Eager loading des relations
- Pagination optimisée
- Requêtes SQL optimisées avec jointures
- Cache des compteurs

## 🔧 Développement

### Régénérer la documentation API
```bash
php artisan l5-swagger:generate
```

### Compiler les assets
```bash
npm run dev          # Mode développement
npm run build        # Mode production
npm run watch        # Watch mode
```

### Base de données
```bash
php artisan migrate:fresh --seed    # Reset complet
php artisan db:seed                 # Ajouter des données
```

## 📊 Données de test

Le système est livré avec des données réalistes :
- **15 auteurs** de la littérature française
- **30 livres** avec prix et dates de publication réels
- **Relations cohérentes** entre auteurs et livres

Auteurs inclus : Victor Hugo, Gustave Flaubert, Marcel Proust, Albert Camus, Simone de Beauvoir, etc.

## 🎯 Critères techniques respectés

✅ **Architecture Laravel** - Utilisation des bonnes pratiques  
✅ **Persistence SQLite** - Base de données intégrée  
✅ **API REST documentée** - Swagger/OpenAPI complet  
✅ **Frontend moderne** - Interface responsive avec Tailwind CSS  
✅ **Tests complets** - 66 tests unitaires et d'intégration  
✅ **Documentation** - API et code documentés  
✅ **Modélisation** - Relations One-to-Many correctes  
✅ **Validation** - Règles métier et validation des données  

## 📚 Apprentissages et défis

### Défis techniques rencontrés :
- **Relations Eloquent** - Optimisation des requêtes avec eager loading
- **Validation métier** - Empêcher la suppression d'auteurs avec des livres
- **Tests API** - Couverture complète des cas limites et erreurs
- **Sécurité** - Séparation lecture publique / écriture authentifiée

### Points d'amélioration identifiés :
- Mise en cache des requêtes fréquentes
- Logs détaillés pour le debugging
- Optimisation des performances sur de gros datasets
- Gestion plus fine des permissions utilisateurs

## 📞 Support

Pour toute question :
1. Consulter la [documentation API](API_DOCUMENTATION.md)
2. Utiliser l'interface Swagger interactive
3. Examiner les tests pour des exemples d'utilisation
4. Vérifier les logs Laravel en cas d'erreur
