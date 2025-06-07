<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:9999.99'],
            'author_id' => ['sometimes', 'required', 'exists:authors,id'],
            'publication_date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être positif.',
            'price.max' => 'Le prix ne peut pas dépasser 9999.99.',
            'author_id.required' => 'L\'auteur est obligatoire.',
            'author_id.exists' => 'L\'auteur sélectionné n\'existe pas.',
            'publication_date.required' => 'La date de publication est obligatoire.',
            'publication_date.date' => 'La date de publication doit être une date valide.',
            'publication_date.before_or_equal' => 'La date de publication ne peut pas être dans le futur.',
        ];
    }
}
