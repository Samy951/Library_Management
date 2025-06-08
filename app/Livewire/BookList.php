<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Author;
use Livewire\Component;
use Livewire\WithPagination;

class BookList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'title';
    public string $sortDirection = 'asc';
    public string $authorFilter = '';
    public string $minPrice = '';
    public string $maxPrice = '';

    protected $listeners = ['bookCreated' => '$refresh', 'bookUpdated' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedAuthorFilter()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function deleteBook($bookId)
    {
        $book = Book::find($bookId);
        
        if ($book) {
            $book->delete();
            session()->flash('success', 'Livre supprimé avec succès.');
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->authorFilter = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->resetPage();
    }

    public function render()
    {
        $books = Book::query()
            ->with('author')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhereHas('author', function ($query) {
                          $query->where('first_name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->authorFilter, function ($query) {
                $query->where('author_id', $this->authorFilter);
            })
            ->when($this->minPrice, function ($query) {
                $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function ($query) {
                $query->where('price', '<=', $this->maxPrice);
            })
            ->when($this->sortBy === 'author_name', function ($query) {
                $query->join('authors', 'books.author_id', '=', 'authors.id')
                      ->orderBy('authors.last_name', $this->sortDirection)
                      ->orderBy('authors.first_name', $this->sortDirection)
                      ->select('books.*');
            }, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate(10);

        $authors = Author::orderBy('last_name')->orderBy('first_name')->get();

        return view('livewire.book-list', compact('books', 'authors'));
    }
}
