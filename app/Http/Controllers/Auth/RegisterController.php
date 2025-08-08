<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Prestataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validation des champs communs d'abord
        $commonRules = [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'user_type' => ['required', 'in:client,prestataire'],
        ];
        
        $commonMessages = [
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'name.regex' => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
            'user_type.required' => 'Vous devez choisir un type de compte.',
            'user_type.in' => 'Le type de compte sélectionné n\'est pas valide.',
        ];
        
        // Validation conditionnelle selon le type d'utilisateur
        if ($request->user_type === 'client') {
            $clientRules = [
                'location' => ['nullable', 'string', 'max:255'],
                'client_profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ];
            
            $clientMessages = [
                'location.max' => 'La localisation ne peut pas dépasser 255 caractères.',
                'client_profile_photo.image' => 'Le fichier doit être une image.',
                'client_profile_photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
                'client_profile_photo.max' => 'L\'image ne peut pas dépasser 2 MB.',
            ];
            
            $request->validate(array_merge($commonRules, $clientRules), array_merge($commonMessages, $clientMessages));
            
        } elseif ($request->user_type === 'prestataire') {
            $prestataireRules = [
                'company_name' => ['required', 'string', 'min:2', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'category_id' => ['required', 'exists:categories,id'],
                'subcategory_id' => ['nullable', 'exists:categories,id'],
                'city' => ['required', 'string', 'max:255'],
                'prestataire_profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'description' => ['nullable', 'string', 'max:500'],
                'portfolio_url' => ['nullable', 'url', 'max:255'],
            ];
            
            $prestataireMessages = [
                'company_name.required' => 'Le nom de l\'enseigne est obligatoire.',
                'company_name.min' => 'Le nom de l\'enseigne doit contenir au moins 2 caractères.',
                'company_name.max' => 'Le nom de l\'enseigne ne peut pas dépasser 255 caractères.',
                'phone.required' => 'Le numéro de téléphone est obligatoire.',
                'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
                'category_id.required' => 'La catégorie est obligatoire.',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
                'subcategory_id.exists' => 'La sous-catégorie sélectionnée n\'existe pas.',
                'city.required' => 'La ville est obligatoire.',
                'city.max' => 'La ville ne peut pas dépasser 255 caractères.',
                'prestataire_profile_photo.required' => 'Une photo de profil est obligatoire pour les prestataires.',
                'prestataire_profile_photo.image' => 'Le fichier doit être une image.',
                'prestataire_profile_photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
                'prestataire_profile_photo.max' => 'L\'image ne peut pas dépasser 2 MB.',
                'description.max' => 'La description ne peut pas dépasser 500 caractères.',
                'portfolio_url.url' => 'Le lien du portfolio doit être une URL valide.',
                'portfolio_url.max' => 'Le lien du portfolio ne peut pas dépasser 255 caractères.',
            ];
            
            $request->validate(array_merge($commonRules, $prestataireRules), array_merge($commonMessages, $prestataireMessages));
        } else {
            // Validation des champs communs seulement si le type n'est pas reconnu
            $request->validate($commonRules, $commonMessages);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->user_type,
        ]);

        // Handle user type specific data (validation already done above)
        if ($request->user_type === 'client') {
            $clientPhotoPath = null;
            if ($request->hasFile('client_profile_photo')) {
                $clientPhotoPath = $request->file('client_profile_photo')->store('profile_photos/clients', 'public');
            }

            Client::create([
                'user_id' => $user->id,
                'photo' => $clientPhotoPath,
                'location' => $request->location,
            ]);
        } elseif ($request->user_type === 'prestataire') {
            $prestatairePhotoPath = null;
            if ($request->hasFile('prestataire_profile_photo')) {
                $prestatairePhotoPath = $request->file('prestataire_profile_photo')->store('profile_photos/prestataires', 'public');
            }

            // Récupérer la catégorie et sous-catégorie
            $category = \App\Models\Category::find($request->category_id);
            $subcategory = $request->subcategory_id ? \App\Models\Category::find($request->subcategory_id) : null;

            Prestataire::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'phone' => $request->phone,
                'city' => $request->city,
                'photo' => $prestatairePhotoPath,
                'secteur_activite' => $category ? $category->name : '',
                'competences' => $subcategory ? $subcategory->name : '',
                'description' => $request->description,
                'portfolio_url' => $request->portfolio_url,
            ]);
        }

        // Connecter automatiquement l'utilisateur après inscription
        auth()->login($user);

        // Redirection intelligente selon le rôle
        if ($user->role === 'client') {
            return redirect()->route('client.dashboard')->with('success', 'Inscription réussie ! Bienvenue sur votre espace client.');
        } elseif ($user->role === 'prestataire') {
            // Redirection directe vers le dashboard prestataire (plus de validation manuelle)
            return redirect()->route('prestataire.dashboard')->with('success', 'Inscription réussie ! Bienvenue sur votre espace prestataire.');
        }
        
        // Fallback vers login si rôle non reconnu
        return redirect('/login')->with('success', 'Inscription réussie !');
    }
}
