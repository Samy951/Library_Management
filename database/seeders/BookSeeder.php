<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            // Victor Hugo
            ['title' => 'Les Misérables', 'author_name' => 'Victor Hugo', 'price' => 15.90, 'publication_date' => '1862-03-30'],
            ['title' => 'Notre-Dame de Paris', 'author_name' => 'Victor Hugo', 'price' => 12.50, 'publication_date' => '1831-03-16'],
            ['title' => 'Les Travailleurs de la mer', 'author_name' => 'Victor Hugo', 'price' => 14.20, 'publication_date' => '1866-03-27'],
            
            // Émile Zola
            ['title' => 'Germinal', 'author_name' => 'Émile Zola', 'price' => 13.80, 'publication_date' => '1885-02-28'],
            ['title' => 'L\'Assommoir', 'author_name' => 'Émile Zola', 'price' => 12.90, 'publication_date' => '1877-01-01'],
            ['title' => 'Nana', 'author_name' => 'Émile Zola', 'price' => 11.95, 'publication_date' => '1880-02-15'],
            
            // Marcel Proust
            ['title' => 'Du côté de chez Swann', 'author_name' => 'Marcel Proust', 'price' => 18.50, 'publication_date' => '1913-11-14'],
            ['title' => 'À l\'ombre des jeunes filles en fleurs', 'author_name' => 'Marcel Proust', 'price' => 19.90, 'publication_date' => '1918-06-30'],
            
            // Albert Camus
            ['title' => 'L\'Étranger', 'author_name' => 'Albert Camus', 'price' => 8.90, 'publication_date' => '1942-06-19'],
            ['title' => 'La Peste', 'author_name' => 'Albert Camus', 'price' => 9.50, 'publication_date' => '1947-06-10'],
            ['title' => 'La Chute', 'author_name' => 'Albert Camus', 'price' => 7.80, 'publication_date' => '1956-05-16'],
            
            // Gustave Flaubert
            ['title' => 'Madame Bovary', 'author_name' => 'Gustave Flaubert', 'price' => 10.90, 'publication_date' => '1857-04-01'],
            ['title' => 'L\'Éducation sentimentale', 'author_name' => 'Gustave Flaubert', 'price' => 13.20, 'publication_date' => '1869-11-17'],
            
            // Stendhal
            ['title' => 'Le Rouge et le Noir', 'author_name' => 'Stendhal Beyle', 'price' => 11.50, 'publication_date' => '1830-11-13'],
            ['title' => 'La Chartreuse de Parme', 'author_name' => 'Stendhal Beyle', 'price' => 12.80, 'publication_date' => '1839-04-06'],
            
            // Honoré de Balzac
            ['title' => 'Le Père Goriot', 'author_name' => 'Honoré Balzac', 'price' => 9.90, 'publication_date' => '1835-03-01'],
            ['title' => 'Eugénie Grandet', 'author_name' => 'Honoré Balzac', 'price' => 8.50, 'publication_date' => '1833-09-01'],
            
            // Alexandre Dumas
            ['title' => 'Les Trois Mousquetaires', 'author_name' => 'Alexandre Dumas', 'price' => 14.90, 'publication_date' => '1844-03-14'],
            ['title' => 'Le Comte de Monte-Cristo', 'author_name' => 'Alexandre Dumas', 'price' => 16.50, 'publication_date' => '1844-08-28'],
            
            // Jules Verne
            ['title' => 'Vingt mille lieues sous les mers', 'author_name' => 'Jules Verne', 'price' => 12.20, 'publication_date' => '1870-06-20'],
            ['title' => 'Le Tour du monde en quatre-vingts jours', 'author_name' => 'Jules Verne', 'price' => 10.80, 'publication_date' => '1873-01-30'],
            
            // Simone de Beauvoir
            ['title' => 'Le Deuxième Sexe', 'author_name' => 'Simone Beauvoir', 'price' => 22.90, 'publication_date' => '1949-06-01'],
            ['title' => 'Les Mandarins', 'author_name' => 'Simone Beauvoir', 'price' => 15.60, 'publication_date' => '1954-10-01'],
            
            // Jean-Paul Sartre
            ['title' => 'La Nausée', 'author_name' => 'Jean-Paul Sartre', 'price' => 9.20, 'publication_date' => '1938-04-18'],
            ['title' => 'Huis clos', 'author_name' => 'Jean-Paul Sartre', 'price' => 6.50, 'publication_date' => '1944-05-27'],
        ];

        foreach ($books as $bookData) {
            $author = Author::where('first_name', 'LIKE', '%' . explode(' ', $bookData['author_name'])[0] . '%')
                           ->where('last_name', 'LIKE', '%' . explode(' ', $bookData['author_name'])[1] . '%')
                           ->first();
            
            if ($author) {
                Book::create([
                    'title' => $bookData['title'],
                    'author_id' => $author->id,
                    'price' => $bookData['price'],
                    'publication_date' => $bookData['publication_date'],
                ]);
            }
        }
    }
}
