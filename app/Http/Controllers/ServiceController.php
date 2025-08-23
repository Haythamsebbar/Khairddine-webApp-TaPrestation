<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Affiche la liste des services publics avec filtrage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Sauvegarder les filtres en session pour pouvoir les récupérer lors du retour
        $filters = $request->only(['search', 'category', 'price_min', 'price_max', 'location', 'verified_only', 'sort']);
        session(['services_filters' => $filters]);
        
        $query = Service::with(['prestataire', 'categories'])
            ->whereHas('prestataire', function($q) {
                $q->where('is_approved', true);
            });
        
        // Recherche par mot-clé
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%')
                  ->orWhereHas('prestataire.user', function($userQuery) use ($keyword) {
                      $userQuery->where('name', 'like', '%' . $keyword . '%');
                  })
                  ->orWhereHas('categories', function($catQuery) use ($keyword) {
                      $catQuery->where('name', 'like', '%' . $keyword . '%');
                  });
            });
        }
        
        // Filtrage par catégorie
        if ($request->filled('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        // Filtrer par prix minimum
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        // Filtrer par prix maximum
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filtrage par localisation
        if ($request->filled('location')) {
            $query->where(function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('postal_code', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }





        // Filtrage pour les prestataires certifiés
        if ($request->has('verified_only')) {
            $query->whereHas('prestataire', function ($q) {
                $q->where('is_verified', true);
            });
        }

        // Tri
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'recent':
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $services = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        
        return view('services.index', compact('services', 'categories'));
    }

    /**
     * Affiche la liste des services du prestataire connecté.
     *
     * @return \Illuminate\View\View
     */
    public function prestataireServices()
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        if (!$prestataire) {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }
        
        $services = Service::where('prestataire_id', $prestataire->id)
            ->with(['categories'])
            ->latest()
            ->get();
            
        return view('prestataire.services.index', compact('services'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau service.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('prestataire.services.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        if (!$prestataire) {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // Champs prix supprimés pour des raisons de confidentialité
            'duration' => 'nullable|integer|min:1',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id'
        ]);
        
        $service = new Service();
        $service->title = $validated['title'];
        $service->description = $validated['description'];
        // Prix supprimés pour des raisons de confidentialité
        $service->duration = $validated['duration'];
        $service->prestataire_id = $prestataire->id;
        $service->status = 'active';
        $service->save();
        
        if (isset($validated['categories'])) {
            $service->categories()->sync($validated['categories']);
        }
        
        return redirect()->route('prestataire.services.index')
            ->with('success', 'Service créé avec succès.');
    }

    /**
     * Affiche un service spécifique.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\View\View
     */
    public function show(Service $service)
    {
        $service->load(['prestataire.user', 'categories', 'reviews.client.user', 'images']);

        // Incrémenter le compteur de vues
        $service->increment('views');

        // Récupérer les services similaires de la même catégorie
        $categoryIds = $service->categories->pluck('id');

        $similarServices = Service::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
        ->where('id', '!=', $service->id)
        ->where('is_visible', true)
        ->latest()
        ->take(4)
        ->get();

        // Calculer la note moyenne
        $averageRating = $service->reviews->avg('rating');
        $totalReviews = $service->reviews->count();

        return view('services.show', compact('service', 'similarServices', 'averageRating', 'totalReviews'));
    }

    /**
     * Affiche le formulaire d'édition d'un service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\View\View
     */
    public function edit(Service $service)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        if (!$prestataire || $service->prestataire_id !== $prestataire->id) {
            return redirect()->route('prestataire.services.index')
                ->with('error', 'Accès non autorisé.');
        }
        
        $categories = Category::orderBy('name')->get();
        return view('prestataire.services.edit', compact('service', 'categories'));
    }

    /**
     * Met à jour un service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Service $service)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        if (!$prestataire || $service->prestataire_id !== $prestataire->id) {
            return redirect()->route('prestataire.services.index')
                ->with('error', 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
            'duration' => 'nullable|integer|min:1',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id'
        ]);
        
        $service->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            // Prix supprimés pour des raisons de confidentialité
            'duration' => $validated['duration']
        ]);
        
        if (isset($validated['categories'])) {
            $service->categories()->sync($validated['categories']);
        }
        
        return redirect()->route('prestataire.services.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    /**
     * Supprime un service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        if (!$prestataire || $service->prestataire_id !== $prestataire->id) {
            return redirect()->route('prestataire.services.index')
                ->with('error', 'Accès non autorisé.');
        }
        
        $service->delete();
        
        return redirect()->route('prestataire.services.index')
            ->with('success', 'Service supprimé avec succès.');
    }
    
    /**
     * Soumettre un signalement pour un service
     */
    public function submitReport(Request $request, Service $service)
    {
        $request->validate([
            'category' => 'required|in:inappropriate_content,fraud,misleading_info,poor_service,pricing_issue,unavailable,spam,copyright,other',
            'reason' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'evidence_photos' => 'nullable|array|max:3',
            'evidence_photos.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $reportData = [
            'service_id' => $service->id,
            'reason' => $request->reason,
            'category' => $request->category,
            'description' => $request->description,
            'reporter_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'priority' => 'medium'
        ];
        
        // Gérer l'utilisateur connecté
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->client) {
                $reportData['reporter_id'] = $user->client->id;
                $reportData['reporter_type'] = 'client';
            } elseif ($user->prestataire) {
                $reportData['reporter_id'] = $user->prestataire->id;
                $reportData['reporter_type'] = 'prestataire';
            }
        } else {
            $reportData['reporter_type'] = 'anonymous';
        }
        
        // Gérer les photos de preuve
        if ($request->hasFile('evidence_photos')) {
            $photos = [];
            foreach ($request->file('evidence_photos') as $photo) {
                $path = $photo->store('service-reports', 'public');
                $photos[] = $path;
            }
            $reportData['evidence_photos'] = $photos;
        }
        
        \App\Models\ServiceReport::create($reportData);
        
        return response()->json([
            'success' => true,
            'message' => 'Votre signalement a été soumis avec succès. Nous examinerons votre demande dans les plus brefs délais.'
        ]);
    }
}