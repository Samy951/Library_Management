# 📚 Documentation API - Système de Gestion de Bibliothèque

## Vue d'ensemble

Cette API REST permet de gérer une bibliothèque avec des auteurs et des livres. Elle est développée avec Laravel 12 et fournit une interface complète pour les opérations CRUD.

## 🔗 Accès à la documentation

- **Documentation interactive Swagger UI** : [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
- **JSON OpenAPI** : [http://localhost:8000/docs/api-docs.json](http://localhost:8000/docs/api-docs.json)

## 🚀 Démarrage rapide

### Installation et configuration

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

### URL de base
```
http://localhost:8000/api
```

## 📖 Endpoints principaux

### 👥 Auteurs (`/authors`)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/authors` | Liste tous les auteurs avec pagination |
| POST | `/authors` | Créer un nouvel auteur |
| GET | `/authors/{id}` | Détails d'un auteur spécifique |
| PUT | `/authors/{id}` | Mettre à jour un auteur |
| DELETE | `/authors/{id}` | Supprimer un auteur |

### 📚 Livres (`/books`)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/books` | Liste tous les livres avec pagination |
| POST | `/books` | Créer un nouveau livre |
| GET | `/books/{id}` | Détails d'un livre spécifique |
| PUT | `/books/{id}` | Mettre à jour un livre |
| DELETE | `/books/{id}` | Supprimer un livre |

## 🔍 Exemples d'utilisation avec curl

### Auteurs

#### Lister tous les auteurs
```bash
curl -X GET "http://localhost:8000/api/authors" \
  -H "Accept: application/json"
```

#### Rechercher des auteurs
```bash
curl -X GET "http://localhost:8000/api/authors?search=Victor&sort=last_name&direction=asc" \
  -H "Accept: application/json"
```

#### Créer un nouvel auteur
```bash
curl -X POST "http://localhost:8000/api/authors" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Antoine",
    "last_name": "de Saint-Exupéry"
  }'
```

#### Afficher un auteur spécifique
```bash
curl -X GET "http://localhost:8000/api/authors/1" \
  -H "Accept: application/json"
```

#### Mettre à jour un auteur
```bash
curl -X PUT "http://localhost:8000/api/authors/1" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Victor",
    "last_name": "Hugo"
  }'
```

#### Supprimer un auteur
```bash
curl -X DELETE "http://localhost:8000/api/authors/1" \
  -H "Accept: application/json"
```

### Livres

#### Lister tous les livres
```bash
curl -X GET "http://localhost:8000/api/books" \
  -H "Accept: application/json"
```

#### Rechercher des livres avec filtres
```bash
curl -X GET "http://localhost:8000/api/books?search=Misérables&min_price=15&max_price=25&sort=price&direction=desc" \
  -H "Accept: application/json"
```

#### Filtrer par auteur
```bash
curl -X GET "http://localhost:8000/api/books?author_id=1" \
  -H "Accept: application/json"
```

#### Créer un nouveau livre
```bash
curl -X POST "http://localhost:8000/api/books" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Le Petit Prince",
    "price": 12.99,
    "publication_date": "1943-04-06",
    "author_id": 2
  }'
```

## 📋 Paramètres de requête

### Auteurs

| Paramètre | Type | Description | Exemple |
|-----------|------|-------------|---------|
| `search` | string | Recherche dans prénom/nom | `?search=Victor` |
| `sort` | string | Champ de tri (`first_name`, `last_name`, `created_at`) | `?sort=last_name` |
| `direction` | string | Direction (`asc`, `desc`) | `?direction=desc` |
| `per_page` | integer | Éléments par page (1-100) | `?per_page=20` |

### Livres

| Paramètre | Type | Description | Exemple |
|-----------|------|-------------|---------|
| `search` | string | Recherche dans titre/auteur | `?search=Misérables` |
| `author_id` | integer | Filtrer par auteur | `?author_id=1` |
| `min_price` | float | Prix minimum | `?min_price=10.00` |
| `max_price` | float | Prix maximum | `?max_price=50.00` |
| `sort` | string | Champ de tri | `?sort=price` |
| `direction` | string | Direction (`asc`, `desc`) | `?direction=desc` |
| `per_page` | integer | Éléments par page (1-100) | `?per_page=25` |

## 📝 Formats de données

### Auteur (Author)

```json
{
  "id": 1,
  "first_name": "Victor",
  "last_name": "Hugo", 
  "full_name": "Victor Hugo",
  "books_count": 3,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Livre (Book)

```json
{
  "id": 1,
  "title": "Les Misérables",
  "price": 19.99,
  "publication_date": "1862-04-03",
  "author_id": 1,
  "author": {
    "id": 1,
    "first_name": "Victor",
    "last_name": "Hugo",
    "full_name": "Victor Hugo",
    "books_count": 3
  },
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

## ⚠️ Règles métier

### Suppression d'auteurs
- Un auteur ne peut pas être supprimé s'il a des livres associés
- Erreur 422 retournée avec le message : `"Impossible de supprimer cet auteur car il a des livres associés."`

### Validation des données

#### Auteur
- `first_name` : requis, string, max 255 caractères
- `last_name` : requis, string, max 255 caractères

#### Livre
- `title` : requis, string, max 255 caractères
- `price` : requis, numérique, entre 0 et 999.99
- `publication_date` : requis, date valide, pas dans le futur
- `author_id` : requis, doit exister dans la table authors

## 🔄 Réponses d'erreur

### 404 - Ressource non trouvée
```json
{
  "message": "No query results for model [App\\Models\\Author] 1"
}
```

### 422 - Erreur de validation
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "first_name": ["Le prénom est obligatoire."],
    "last_name": ["Le nom de famille est obligatoire."]
  }
}
```

### 422 - Règle métier violée
```json
{
  "message": "Impossible de supprimer cet auteur car il a des livres associés.",
  "error": "AUTHOR_HAS_BOOKS"
}
```

## 📊 Structure de pagination

```json
{
  "data": [...],
  "links": {
    "first": "http://localhost:8000/api/authors?page=1",
    "last": "http://localhost:8000/api/authors?page=2",
    "prev": null,
    "next": "http://localhost:8000/api/authors?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "per_page": 15,
    "to": 15,
    "total": 20
  }
}
```

## 🧪 Tests

### Exécuter les tests
```bash
# Tous les tests
php artisan test

# Tests API seulement
php artisan test --filter="AuthorApiTest|BookApiTest"
```

### Couverture des tests
- **36 tests** couvrent l'API complète
- Tests CRUD complets pour Authors et Books
- Tests de validation et règles métier
- Tests de recherche, tri et pagination

## 🔧 Développement

### Régénérer la documentation
```bash
php artisan l5-swagger:generate
```

### Structure du projet
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthorController.php    # CRUD auteurs
│   │   └── BookController.php      # CRUD livres
│   ├── Requests/                   # Validation des requêtes
│   └── Resources/                  # Formatage des réponses JSON
├── Models/
│   ├── Author.php                  # Modèle auteur
│   └── Book.php                    # Modèle livre
tests/Feature/
├── AuthorApiTest.php               # Tests API auteurs
└── BookApiTest.php                 # Tests API livres
```

## 📞 Support

Pour toute question ou problème, consultez :
1. La documentation Swagger interactive
2. Les tests pour voir des exemples d'utilisation
3. Le code source des contrôleurs pour la logique métier 