# 🔐 Authentification API : Pourquoi Sanctum ?

## 🎯 **Contexte du projet**

Notre API de bibliothèque utilise actuellement l'authentification **Breeze** (sessions web) pour protéger les endpoints d'écriture. Mais **pourquoi considérer Sanctum** ?

## 🔄 **Comparaison des approches**

### **1. Breeze (Actuel) - Auth par sessions**
```php
// routes/api.php
Route::middleware(['auth'])->group(function () {
    Route::post('books', [BookController::class, 'store']);
});

// Dans les tests
$this->actingAs($user)->postJson('/api/books', $data);
```

**✅ Avantages :**
- Simple à implémenter
- Fonctionne avec l'interface web existante
- Pas de gestion de tokens

**❌ Inconvénients :**
- Limité aux applications web (cookies/sessions)
- Problématique pour les apps mobiles
- CSRF protection nécessaire
- Pas adapté aux API découplées

### **2. Sanctum - Auth par tokens**
```php
// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('books', [BookController::class, 'store']);
});

// Génération de token
$token = $user->createToken('mobile-app')->plainTextToken;

// Utilisation
curl -H "Authorization: Bearer $token" /api/books
```

**✅ Avantages :**
- **Stateless** - Pas de sessions
- **Multi-plateforme** - Web, mobile, desktop
- **Scopes/Permissions** - Tokens avec droits limités
- **Révocation** - Contrôle fin des accès
- **API-first** - Conçu pour les APIs modernes

## 🚀 **Cas d'usage concrets pour Sanctum**

### **Scénario 1 : Application mobile**
```php
// Une app mobile pour consulter/emprunter des livres
$user = User::find(1);
$token = $user->createToken('mobile-library-app', ['books:read', 'books:borrow'])->plainTextToken;

// L'app mobile utilise ce token pour toutes ses requêtes
// Pas de cookies, pas de sessions, juste le token
```

### **Scénario 2 : API publique avec quotas**
```php
// Différents niveaux d'accès
$publicToken = $user->createToken('public-api', ['books:read'])->plainTextToken;
$adminToken = $user->createToken('admin-panel', ['*'])->plainTextToken;

// Tracking et limitation par token
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('books', [BookController::class, 'index']);
});
```

### **Scénario 3 : Intégrations tierces**
```php
// Une librairie partenaire veut accéder à notre catalogue
$partnerToken = $partner->createToken('partner-integration', [
    'books:read',
    'authors:read'
])->plainTextToken;

// Ils peuvent intégrer notre catalogue sans accès complet
```

### **Scénario 4 : Dashboard admin séparé**
```php
// Frontend React/Vue séparé pour l'administration
$adminToken = $admin->createToken('admin-dashboard', [
    'books:*',
    'authors:*',
    'users:read'
])->plainTextToken;

// Interface moderne découplée du backend Laravel
```

## 🔧 **Implémentation Sanctum dans notre projet**

### **Installation et configuration**
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### **Modèle User**
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    // Scopes personnalisés
    public function createLibraryToken(array $abilities = ['*'])
    {
        return $this->createToken('library-access', $abilities);
    }
}
```

### **Routes avec scopes**
```php
// Lecture publique (pas d'auth)
Route::get('books', [BookController::class, 'index']);
Route::get('authors', [AuthorController::class, 'index']);

// Écriture avec authentification
Route::middleware(['auth:sanctum'])->group(function () {
    // Gestion des livres
    Route::middleware(['ability:books:create'])->group(function () {
        Route::post('books', [BookController::class, 'store']);
    });
    
    Route::middleware(['ability:books:update'])->group(function () {
        Route::put('books/{book}', [BookController::class, 'update']);
    });
    
    Route::middleware(['ability:books:delete'])->group(function () {
        Route::delete('books/{book}', [BookController::class, 'destroy']);
    });
});
```

### **Contrôleur d'authentification**
```php
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-access', [
            'books:read',
            'books:create',
            'authors:read'
        ])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'abilities' => ['books:read', 'books:create', 'authors:read']
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
```

## 📱 **Exemples d'utilisation**

### **Frontend JavaScript (SPA)**
```javascript
// Login et récupération du token
const response = await fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
});

const { token } = await response.json();
localStorage.setItem('api_token', token);

// Utilisation du token
const books = await fetch('/api/books', {
    headers: { 'Authorization': `Bearer ${token}` }
});
```

### **Application mobile (React Native)**
```javascript
// Stockage sécurisé du token
import AsyncStorage from '@react-native-async-storage/async-storage';

const storeToken = async (token) => {
    await AsyncStorage.setItem('library_token', token);
};

const apiCall = async (endpoint) => {
    const token = await AsyncStorage.getItem('library_token');
    return fetch(`https://api.library.com${endpoint}`, {
        headers: { 'Authorization': `Bearer ${token}` }
    });
};
```

### **CLI Tool**
```bash
# Génération d'un token pour un outil CLI
php artisan tinker
>>> $user = User::find(1)
>>> $token = $user->createToken('cli-tool', ['books:read'])->plainTextToken
>>> echo $token

# Utilisation
curl -H "Authorization: Bearer $token" https://api.library.com/api/books
```

## 🛡️ **Sécurité avancée**

### **Révocation de tokens**
```php
// Révoquer un token spécifique
$user->tokens()->where('name', 'mobile-app')->delete();

// Révoquer tous les tokens
$user->tokens()->delete();

// Révoquer le token actuel
$request->user()->currentAccessToken()->delete();
```

### **Limitation par scopes**
```php
// Middleware personnalisé pour vérifier les permissions
class CheckBookPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!$request->user()->tokenCan("books:$permission")) {
            return response()->json(['error' => 'Insufficient permissions'], 403);
        }
        
        return $next($request);
    }
}

// Utilisation
Route::middleware(['auth:sanctum', 'book.permission:create'])
    ->post('books', [BookController::class, 'store']);
```

## 🎯 **Quand utiliser Sanctum ?**

### **✅ Utilise Sanctum si :**
- Application mobile native
- Frontend SPA découplé (React, Vue, Angular)
- API publique avec différents niveaux d'accès
- Intégrations tierces
- Microservices architecture
- Besoin de contrôle fin des permissions
- Multi-tenant avec isolation des données

### **❌ Reste avec Breeze si :**
- Application web monolithique simple
- Pas d'API externe
- Pas d'applications mobiles prévues
- Équipe peu familière avec les tokens
- Projet de test/prototype rapide

## 🔄 **Migration Breeze → Sanctum**

### **Étape 1 : Installation**
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### **Étape 2 : Modèle User**
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

### **Étape 3 : Routes**
```php
// Remplacer
Route::middleware(['auth'])->group(function () {
    // routes protégées
});

// Par
Route::middleware(['auth:sanctum'])->group(function () {
    // routes protégées
});
```

### **Étape 4 : Tests**
```php
// Remplacer
$this->actingAs($user)->postJson('/api/books', $data);

// Par
Sanctum::actingAs($user, ['books:create']);
$this->postJson('/api/books', $data);
```

## 📊 **Comparaison finale**

| Critère | Breeze (Sessions) | Sanctum (Tokens) |
|---------|------------------|------------------|
| **Simplicité** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| **Flexibilité** | ⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Mobile-friendly** | ❌ | ✅ |
| **API découplée** | ❌ | ✅ |
| **Permissions fines** | ❌ | ✅ |
| **Scalabilité** | ⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Sécurité** | ⭐⭐⭐ | ⭐⭐⭐⭐ |

## 🎯 **Conclusion**

**Pour notre projet bibliothèque :**

- **Phase 1 (Actuelle)** : Breeze pour le MVP et les tests
- **Phase 2 (Production)** : Sanctum pour l'évolutivité et les intégrations futures

**Sanctum apporte :**
- Architecture moderne et évolutive
- Support multi-plateforme natif
- Contrôle granulaire des accès
- Préparation pour les futures intégrations

**Le choix dépend de tes objectifs :**
- **Prototype/Test** → Breeze (plus simple)
- **Production/Évolution** → Sanctum (plus flexible) 