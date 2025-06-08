<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Author;
use Livewire\Component;

class BookForm extends Component
{
    public ?Book $book = null;
    public string $title = '';
    public string $price = '';
    public string $publication_date = '';
    public string $author_id = '';
    public bool $isEdit = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0|max:999.99',
        'publication_date' => 'required|date|before_or_equal:today',
        'author_id' => 'required|exists:authors,id',
    ];

    protected $messages = [
        'title.required' => 'Le titre est obligatoire.',
        'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
        'price.required' => 'Le prix est obligatoire.',
        'price.numeric' => 'Le prix doit être un nombre valide.',
        'price.min' => 'Le prix ne peut pas être négatif.',
        'price.max' => 'Le prix ne peut pas dépasser 999,99 €.',
        'publication_date.required' => 'La date de publication est obligatoire.',
        'publication_date.date' => 'La date de publication doit être une date valide.',
        'publication_date.before_or_equal' => 'La date de publication ne peut pas être dans le futur.',
        'author_id.required' => 'L\'auteur est obligatoire.',
        'author_id.exists' => 'L\'auteur sélectionné n\'existe pas.',
    ];

    public function mount(?Book $book = null)
    {
        if ($book && $book->exists) {
            $this->book = $book;
            $this->title = $book->title;
            $this->price = number_format($book->price, 2, '.', '');
            $this->publication_date = $book->publication_date->format('Y-m-d');
            $this->author_id = (string) $book->author_id;
            $this->isEdit = true;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'price' => $this->price,
            'publication_date' => $this->publication_date,
            'author_id' => $this->author_id,
        ];

        if ($this->isEdit) {
            $this->book->update($data);
            session()->flash('success', 'Livre modifié avec succès.');
            $this->dispatch('bookUpdated');
        } else {
            Book::create($data);
            session()->flash('success', 'Livre créé avec succès.');
            $this->dispatch('bookCreated');
            $this->reset(['title', 'price', 'publication_date', 'author_id']);
        }
    }

    public function cancel()
    {
        $this->reset(['title', 'price', 'publication_date', 'author_id']);
        $this->resetValidation();
    }

    public function render()
    {
        $authors = Author::orderBy('last_name')->orderBy('first_name')->get();
        
        return view('livewire.book-form', compact('authors'));
    }
}
