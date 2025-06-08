<div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $isEdit ? 'Modifier le livre' : 'Nouveau livre' }}
        </h3>
        <p class="text-sm text-gray-600 mt-1">
            {{ $isEdit ? 'Modifiez les informations du livre.' : 'Remplissez les informations du nouveau livre.' }}
        </p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <!-- Titre -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Titre <span class="text-red-500">*</span>
            </label>
            <input 
                wire:model.live.debounce.300ms="title" 
                type="text" 
                id="title"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                placeholder="Entrez le titre du livre"
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Auteur -->
        <div>
            <label for="author_id" class="block text-sm font-medium text-gray-700 mb-1">
                Auteur <span class="text-red-500">*</span>
            </label>
            <select 
                wire:model.live="author_id" 
                id="author_id"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('author_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                <option value="">Sélectionnez un auteur</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->full_name }}</option>
                @endforeach
            </select>
            @error('author_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Prix -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                    Prix <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        wire:model.live.debounce.300ms="price" 
                        type="number" 
                        id="price"
                        step="0.01"
                        min="0"
                        max="999.99"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-8 @error('price') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="0.00"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm">€</span>
                    </div>
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date de publication -->
            <div>
                <label for="publication_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Date de publication <span class="text-red-500">*</span>
                </label>
                <input 
                    wire:model.live="publication_date" 
                    type="date" 
                    id="publication_date"
                    max="{{ date('Y-m-d') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('publication_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                >
                @error('publication_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Aperçu des informations -->
        @if($title && $author_id && $price)
            <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                <p class="text-sm text-gray-600 mb-2">Aperçu :</p>
                <div class="space-y-1">
                    <p class="font-medium text-gray-900">{{ $title }}</p>
                    <p class="text-sm text-gray-600">
                        Par : {{ $authors->find($author_id)?->full_name ?? 'Auteur non trouvé' }}
                    </p>
                    @if($price)
                        <p class="text-sm font-medium text-green-600">{{ number_format($price, 2) }} €</p>
                    @endif
                    @if($publication_date)
                        <p class="text-xs text-gray-500">
                            Publié le : {{ \Carbon\Carbon::parse($publication_date)->format('d/m/Y') }}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Boutons d'action -->
        <div class="flex space-x-3 pt-4">
            <button 
                type="submit" 
                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                {{ $isEdit ? 'Mettre à jour' : 'Créer le livre' }}
            </button>
            
            <button 
                type="button" 
                wire:click="cancel"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                {{ $isEdit ? 'Annuler' : 'Effacer' }}
            </button>
        </div>
    </form>

    <!-- Informations complémentaires en mode édition -->
    @if($isEdit && $book)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Informations</h4>
            <div class="space-y-1 text-xs text-gray-500">
                <p>ID : <span class="font-medium">#{{ $book->id }}</span></p>
                <p>Créé le : <span class="font-medium">{{ $book->created_at->format('d/m/Y à H:i') }}</span></p>
                @if($book->updated_at != $book->created_at)
                    <p>Modifié le : <span class="font-medium">{{ $book->updated_at->format('d/m/Y à H:i') }}</span></p>
                @endif
            </div>
        </div>
    @endif
</div>
