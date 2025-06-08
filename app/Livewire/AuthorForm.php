<?php

namespace App\Livewire;

use App\Models\Author;
use Livewire\Component;

class AuthorForm extends Component
{
    public ?Author $author = null;
    public string $first_name = '';
    public string $last_name = '';
    public bool $isEdit = false;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
    ];

    protected $messages = [
        'first_name.required' => 'Le prénom est obligatoire.',
        'first_name.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
        'last_name.required' => 'Le nom de famille est obligatoire.',
        'last_name.max' => 'Le nom de famille ne peut pas dépasser 255 caractères.',
    ];

    public function mount(?Author $author = null)
    {
        if ($author && $author->exists) {
            $this->author = $author;
            $this->first_name = $author->first_name;
            $this->last_name = $author->last_name;
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];

        if ($this->isEdit) {
            $this->author->update($data);
            session()->flash('success', 'Auteur modifié avec succès.');
            $this->dispatch('authorUpdated');
        } else {
            Author::create($data);
            session()->flash('success', 'Auteur créé avec succès.');
            $this->dispatch('authorCreated');
            $this->reset(['first_name', 'last_name']);
        }
    }

    public function cancel()
    {
        $this->reset(['first_name', 'last_name']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.author-form');
    }
}
