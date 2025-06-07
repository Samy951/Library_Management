<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Statistiques de la Bibliothèque</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-500 rounded-full">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Livres</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Book::count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-500 rounded-full">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Auteurs</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Author::count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-500 rounded-full">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Valeur Total</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format(\App\Models\Book::sum('price'), 2) }}€</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold mb-4">Derniers Livres Ajoutés</h4>
                            <div class="space-y-3">
                                @foreach(\App\Models\Book::with('author')->latest()->take(5)->get() as $book)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium">{{ $book->title }}</p>
                                            <p class="text-sm text-gray-600">par {{ $book->author->full_name }}</p>
                                        </div>
                                        <span class="text-sm font-semibold">{{ number_format($book->price, 2) }}€</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold mb-4">Auteurs les plus prolifiques</h4>
                            <div class="space-y-3">
                                @foreach(\App\Models\Author::withCount('books')->orderBy('books_count', 'desc')->take(5)->get() as $author)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium">{{ $author->full_name }}</p>
                                        </div>
                                        <span class="text-sm font-semibold">{{ $author->books_count }} livre(s)</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <div class="inline-flex rounded-md shadow">
                            <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-l-md text-white bg-blue-600 hover:bg-blue-700">
                                Gérer les Livres
                            </a>
                            <a href="{{ route('authors.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-r-md text-blue-600 bg-white hover:bg-gray-50 border-l border-blue-600">
                                Gérer les Auteurs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
