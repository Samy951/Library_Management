<div>
    <p class="text-gray-600 mb-4">Interface de gestion des livres en cours de développement...</p>
    
    <div class="bg-gray-50 p-6 rounded-lg">
        <h4 class="font-medium text-gray-900 mb-2">Aperçu des livres via API</h4>
        <p class="text-sm text-gray-600 mb-4">
            Vous pouvez tester l'API REST à l'adresse : 
            <code class="bg-gray-200 px-2 py-1 rounded text-xs">GET /api/books</code>
        </p>
        
        <div class="text-sm text-gray-500">
            Total des livres dans la base : <strong>{{ \App\Models\Book::count() }}</strong>
        </div>
    </div>
</div>
