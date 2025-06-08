<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $isEdit ? 'Modifier l\'auteur' : 'Nouvel auteur' }}
        </h3>
        <p class="text-sm text-gray-600 mt-1">
            {{ $isEdit ? 'Modifiez les informations de l\'auteur.' : 'Remplissez les informations du nouvel auteur.' }}
        </p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <!-- Prénom -->
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                Prénom <span class="text-red-500">*</span>
            </label>
            <input 
                wire:model.live.debounce.300ms="first_name" 
                type="text" 
                id="first_name"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('first_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                placeholder="Entrez le prénom"
            >
            @error('first_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nom de famille -->
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                Nom de famille <span class="text-red-500">*</span>
            </label>
            <input 
                wire:model.live.debounce.300ms="last_name" 
                type="text" 
                id="last_name"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('last_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                placeholder="Entrez le nom de famille"
            >
            @error('last_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Aperçu du nom complet -->
        @if($first_name || $last_name)
            <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                <p class="text-sm text-gray-600">Aperçu :</p>
                <p class="font-medium text-gray-900">{{ trim($first_name . ' ' . $last_name) }}</p>
            </div>
        @endif

        <!-- Boutons d'action -->
        <div class="flex space-x-3 pt-4">
            <button 
                type="submit" 
                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                {{ $isEdit ? 'Mettre à jour' : 'Créer l\'auteur' }}
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
    @if($isEdit && $author)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Informations</h4>
            <div class="space-y-1 text-xs text-gray-500">
                <p>Nombre de livres : <span class="font-medium">{{ $author->books()->count() }}</span></p>
                <p>Créé le : <span class="font-medium">{{ $author->created_at->format('d/m/Y à H:i') }}</span></p>
                @if($author->updated_at != $author->created_at)
                    <p>Modifié le : <span class="font-medium">{{ $author->updated_at->format('d/m/Y à H:i') }}</span></p>
                @endif
            </div>
        </div>
    @endif
</div>
