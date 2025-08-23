<?php

namespace App\Http\Controllers\Prestataire;

use App\Http\Controllers\Controller;
use App\Models\UrgentSale;
use App\Models\UrgentSaleContact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UrgentSaleController extends Controller
{
    /**
     * Afficher la liste des ventes urgentes du prestataire
     */
    public function index(Request $request)
    {
        $prestataire = Auth::user()->prestataire;
        
        $query = $prestataire->urgentSales()
                            ->with(['category', 'contacts'])
                            ->withCount(['contacts', 'reports']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $urgentSales = $query->latest()->paginate(12);
        
        // Statistiques
        $stats = [
            'total' => $prestataire->urgentSales()->count(),
            'active' => $prestataire->urgentSales()->where('status', 'active')->count(),
            'sold' => $prestataire->urgentSales()->where('status', 'sold')->count(),
            'inactive' => $prestataire->urgentSales()->where('status', 'inactive')->count(),
            'total_views' => $prestataire->urgentSales()->sum('views_count'),
            'total_contacts' => $prestataire->urgentSales()->withCount('contacts')->get()->sum('contacts_count'),
        ];
        
        return view('prestataire.urgent-sales.index', compact('urgentSales', 'stats'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->with(['children' => function($query) {
                                $query->where('is_active', true)->orderBy('name');
                            }])
                            ->orderBy('name')
                            ->get();
        
        return view('prestataire.urgent-sales.create', compact('categories'));
    }
    
    /**
     * Enregistrer une nouvelle vente urgente
     */
    public function store(Request $request)
    {
        $prestataire = Auth::user()->prestataire;
        
        $request->validate([
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50|max:2000',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:excellent,very_good,good,fair,poor',
            'parent_category_id' => 'required|exists:categories,id',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'location' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
        
        $urgentSale = new UrgentSale();
        $urgentSale->prestataire_id = $prestataire->id;
        $urgentSale->title = $request->title;
        $urgentSale->slug = Str::slug($request->title . '-' . time());
        $urgentSale->description = $request->description;
        $urgentSale->price = $request->price;
        $urgentSale->condition = $request->condition;
        $urgentSale->category_id = $request->category_id ?: $request->parent_category_id;
        $urgentSale->quantity = $request->quantity;
        $urgentSale->location = $request->location;
        $urgentSale->latitude = $request->latitude;
        $urgentSale->longitude = $request->longitude;
        $urgentSale->status = 'active';
        
        // Gestion des photos
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('urgent-sales', 'public');
                $photos[] = $path;
            }
            $urgentSale->photos = json_encode($photos);
        }
        
        $urgentSale->save();
        
        return redirect()->route('prestataire.urgent-sales.index')
                        ->with('success', 'Vente urgente créée avec succès!');
    }
    
    /**
     * Afficher une vente urgente spécifique
     */
    public function show(UrgentSale $urgentSale)
    {
        $this->authorize('view', $urgentSale);
        
        $urgentSale->load(['category', 'contacts.user', 'reports']);
        
        // Récupérer les messages liés à cette vente urgente
        $relatedMessages = \App\Models\Message::where('receiver_id', Auth::id())
            ->where('content', 'like', '%' . $urgentSale->title . '%')
            ->with('sender')
            ->latest()
            ->limit(10)
            ->get();
        
        return view('prestataire.urgent-sales.show', compact('urgentSale', 'relatedMessages'));
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(UrgentSale $urgentSale)
    {
        $this->authorize('update', $urgentSale);
        
        $categories = Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->with(['children' => function($query) {
                                $query->where('is_active', true)->orderBy('name');
                            }])
                            ->orderBy('name')
                            ->get();
        
        return view('prestataire.urgent-sales.edit', compact('urgentSale', 'categories'));
    }
    
    /**
     * Mettre à jour une vente urgente
     */
    public function update(Request $request, UrgentSale $urgentSale)
    {
        $this->authorize('update', $urgentSale);
        
        $request->validate([
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50|max:2000',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:excellent,very_good,good,fair,poor',
            'parent_category_id' => 'required|exists:categories,id',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'location' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
        
        $urgentSale->title = $request->title;
        $urgentSale->slug = Str::slug($request->title . '-' . $urgentSale->id);
        $urgentSale->description = $request->description;
        $urgentSale->price = $request->price;
        $urgentSale->condition = $request->condition;
        $urgentSale->category_id = $request->category_id ?: $request->parent_category_id;
        $urgentSale->quantity = $request->quantity;
        $urgentSale->location = $request->location;
        $urgentSale->latitude = $request->latitude;
        $urgentSale->longitude = $request->longitude;
        
        // Gestion des nouvelles photos
        if ($request->hasFile('photos')) {
            // Supprimer les anciennes photos
            if ($urgentSale->photos) {
                $oldPhotos = json_decode($urgentSale->photos, true);
                foreach ($oldPhotos as $oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
            
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('urgent-sales', 'public');
                $photos[] = $path;
            }
            $urgentSale->photos = json_encode($photos);
        }
        
        $urgentSale->save();
        
        return redirect()->route('prestataire.urgent-sales.index')
                        ->with('success', 'Vente urgente mise à jour avec succès!');
    }
    
    /**
     * Supprimer une vente urgente
     */
    public function destroy(UrgentSale $urgentSale)
    {
        $this->authorize('delete', $urgentSale);
        
        // Supprimer les images
        if ($urgentSale->images) {
            $images = json_decode($urgentSale->images, true);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $urgentSale->delete();
        
        return redirect()->route('prestataire.urgent-sales.index')
                        ->with('success', 'Vente urgente supprimée avec succès!');
    }
    
    /**
     * Mettre à jour le statut d'une vente urgente
     */
    public function updateStatus(Request $request, UrgentSale $urgentSale)
    {
        $this->authorize('update', $urgentSale);
        
        $request->validate([
            'status' => 'required|in:active,inactive,sold,expired'
        ]);
        
        $urgentSale->status = $request->status;
        $urgentSale->save();
        
        return back()->with('success', 'Statut mis à jour avec succès!');
    }
    
    /**
     * Afficher les contacts pour une vente urgente
     */
    public function contacts(UrgentSale $urgentSale)
    {
        $this->authorize('view', $urgentSale);
        
        $contacts = $urgentSale->contacts()
                              ->with('user')
                              ->latest()
                              ->paginate(10);
        
        return view('prestataire.urgent-sales.contacts', compact('urgentSale', 'contacts'));
    }
    
    /**
     * Répondre à un contact
     */
    public function respondToContact(Request $request, UrgentSaleContact $contact)
    {
        $this->authorize('view', $contact->urgentSale);
        
        $request->validate([
            'response' => 'required|string|max:1000'
        ]);
        
        $contact->response = $request->response;
        $contact->responded_at = now();
        $contact->save();
        
        return back()->with('success', 'Réponse envoyée avec succès!');
    }
    
    /**
     * Accepter un contact
     */
    public function acceptContact(UrgentSaleContact $contact)
    {
        $this->authorize('view', $contact->urgentSale);
        
        $contact->status = 'accepted';
        $contact->save();
        
        return back()->with('success', 'Contact accepté!');
    }
    
    /**
     * Rejeter un contact
     */
    public function rejectContact(UrgentSaleContact $contact)
    {
        $this->authorize('view', $contact->urgentSale);
        
        $contact->status = 'rejected';
        $contact->save();
        
        return back()->with('success', 'Contact rejeté!');
    }
    
    /**
     * Récupérer les sous-catégories d'une catégorie parent
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(['id', 'name']);
        
        return response()->json($subcategories);
    }
}