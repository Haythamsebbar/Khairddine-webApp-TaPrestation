<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'delivery_time' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'nullable|string|in:fixe,heure,jour,projet,devis',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du service est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du service est obligatoire.',
            'category_id.required' => 'Veuillez sélectionner une catégorie principale.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'subcategory_id.exists' => 'La sous-catégorie sélectionnée n\'existe pas.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'delivery_time.integer' => 'Le délai de livraison doit être un nombre entier.',
            'delivery_time.min' => 'Le délai de livraison ne peut pas être négatif.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Les images doivent être au format JPEG, PNG, JPG, GIF ou SVG.',
            'images.*.max' => 'Chaque image ne peut pas dépasser 2 MB.',
        ];
    }
}
