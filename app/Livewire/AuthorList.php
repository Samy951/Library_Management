<?php

namespace App\Livewire;

use App\Models\Author;
use Livewire\Component;
use Livewire\WithPagination;

class AuthorList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'last_name';
    public string $sortDirection = 'asc';

    protected $listeners = ['authorCreated' => '$refresh', 'authorUpdated' => '$refresh'];

    public function updatedSearch()
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

    public function deleteAuthor($authorId)
    {
        $author = Author::find($authorId);
        
        if ($author && $author->books()->count() === 0) {
            $author->delete();
            session()->flash('success', 'Auteur supprimé avec succès.');
        } else {
            session()->flash('error', 'Impossible de supprimer cet auteur car il a des livres associés.');
        }
    }

    public function render()
    {
        $authors = Author::query()
            ->withCount('books')
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.author-list', compact('authors'));
    }
}
