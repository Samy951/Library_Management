<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Système de Gestion de Bibliothèque') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .gradient-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .floating-orb {
                background: linear-gradient(45deg, #3b82f6, #8b5cf6, #06b6d4);
                background-size: 400% 400%;
                animation: gradient-shift 15s ease infinite;
            }
            @keyframes gradient-shift {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .card-hover {
                transition: all 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-50 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold gradient-text">📚 Bibliothèque</h1>
                        </div>
                    </div>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">
                                    Connexion
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        S'inscrire
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden gradient-bg">
            <!-- Floating Orbs -->
            <div class="absolute top-20 left-10 w-32 h-32 floating-orb rounded-full opacity-20 float-animation"></div>
            <div class="absolute bottom-20 right-10 w-24 h-24 floating-orb rounded-full opacity-30 float-animation" style="animation-delay: -2s;"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 floating-orb rounded-full opacity-25 float-animation" style="animation-delay: -4s;"></div>
            
            <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white mb-8">
                    <span class="text-sm font-medium">✨ Solution complète de gestion</span>
                </div>
                
                <!-- Main Title -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                    Système de Gestion de
                    <span class="block text-yellow-300">Bibliothèque</span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-xl md:text-2xl text-white mb-12 max-w-3xl mx-auto leading-relaxed">
                    Solution moderne avec API REST complète. Gérez vos livres et auteurs facilement avec une interface intuitive et sécurisée.
                </p>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 rounded-lg p-2 font-semibold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 shadow-xl">
                            <span>Accéder au Dashboard</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 rounded-lg p-2 font-semibold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 shadow-xl">
                            <span>Commencer maintenant</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    @endauth
                    <a href="#api" class="inline-flex items-center px-8 py-4 border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-indigo-600 transition-all">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        <span>Voir l'API</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                        <span class="text-sm font-medium">🚀 Fonctionnalités</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Tout ce dont vous avez besoin
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto p-2">
                        Une solution complète pour gérer votre bibliothèque avec une interface moderne et une API REST documentée.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="card-hover bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 p-8 rounded-3xl border border-blue-200 dark:border-blue-800">
                        <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white rounded-2xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Gestion des Livres</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            CRUD complet avec recherche, tri et pagination. Validation des données et gestion des relations auteur-livre.
                        </p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <li>• Ajout, modification, suppression</li>
                            <li>• Recherche avancée</li>
                            <li>• Gestion des prix et dates</li>
                        </ul>
                    </div>

                    <!-- Feature 2 -->
                    <div class="card-hover bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 p-8 rounded-3xl border border-green-200 dark:border-green-800">
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Gestion des Auteurs</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Gestion complète des auteurs avec relations vers leurs livres. Règles métier intelligentes et validation.
                        </p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <li>• Profils complets</li>
                            <li>• Relations avec livres</li>
                            <li>• Statistiques auteur</li>
                        </ul>
                    </div>

                    <!-- Feature 3 -->
                    <div class="card-hover bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 p-8 rounded-3xl border border-purple-200 dark:border-purple-800">
                        <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">API REST Documentée</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            API complète avec documentation Swagger/OpenAPI interactive. Tests automatisés et sécurité intégrée.
                        </p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <li>• Documentation interactive</li>
                            <li>• Authentification sécurisée</li>
                            <li>• Tests end-to-end</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-gray-100 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Une bibliothèque riche avec des œuvres de la littérature française
                    </h2>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8 text-center">
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-lg">
                        <div class="text-4xl font-bold text-indigo-600 mb-2">500+</div>
                        <div class="text-gray-600 dark:text-gray-400">Livres disponibles</div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-lg">
                        <div class="text-4xl font-bold text-green-600 mb-2">150+</div>
                        <div class="text-gray-600 dark:text-gray-400">Auteurs référencés</div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-lg">
                        <div class="text-4xl font-bold text-white mb-2">100%</div>
                        <div class="text-gray-600 dark:text-gray-400">Tests passants</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- API Section -->
        <section id="api" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mb-6">
                            <span class="text-sm font-medium">🔗 API REST</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                            API Moderne et Documentée
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                            Une API RESTful complète avec documentation interactive, authentification sécurisée et tests automatisés.
                        </p>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-4"></div>
                                <span class="text-gray-700 dark:text-gray-300 pl-2">Endpoints CRUD complets</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-4"></div>
                                <span class="text-gray-700 dark:text-gray-300">Documentation Swagger/OpenAPI</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-4"></div>
                                <span class="text-gray-700 dark:text-gray-300">Authentification JWT</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-4"></div>
                                <span class="text-gray-700 dark:text-gray-300">Tests automatisés complets</span>
                            </li>
                        </ul>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="/api/documentation" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                                Documentation interactive
                            </a>
                        </div>
                    </div>
                    <div class="bg-gray-900 rounded-2xl p-6 overflow-hidden">
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <span class="ml-4 text-gray-400 text-sm">API Endpoint</span>
                        </div>
                        <pre class="text-green-400 text-sm overflow-x-auto"><code class="language-json text-white">GET /api/books
{
  "data": [
    {
      "id": 1,
      "titre": "Les Misérables",
      "prix": 25.99,
      "date_publication": "1862-01-01",
      "auteur": {
        "id": 1,
        "nom": "Hugo",
        "prenom": "Victor"
      }
    }
  ],
  "meta": {
    "total": 500,
    "per_page": 15,
    "current_page": 1
  }
}</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-xl font-bold gradient-text mb-4">📚 Bibliothèque</h3>
                        <p class="text-gray-400">
                            Système moderne de gestion de bibliothèque avec API REST complète et interface intuitive.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Fonctionnalités</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">Gestion des livres</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Gestion des auteurs</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">API REST</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Technologies</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>Laravel 12</li>
                            <li>PHP 8.2+</li>
                            <li>SQLite</li>
                            <li>Swagger/OpenAPI</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Système de Gestion de Bibliothèque. Développé avec ❤️ et Laravel.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
