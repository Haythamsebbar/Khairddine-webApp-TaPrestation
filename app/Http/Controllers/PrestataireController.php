<?php

namespace App\Http\Controllers;

use App\Models\Prestataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Category;

class PrestataireController extends Controller
{
    /**
     * Affiche la liste des prestataires approuvés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Prestataire::with(['user', 'services'])
            ->where('is_approved', true);
        
        // Filtrage par nom
        if ($request->has('name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }
        
        // Filtrage par secteur d'activité
        if ($request->has('secteur')) {
            $query->where('secteur_activite', 'like', '%' . $request->secteur . '%');
        }
        
        // Filtrage par catégorie de service
        if ($request->has('category')) {
            $query->whereHas('services', function($q) use ($request) {
                $q->whereHas('categories', function($q2) use ($request) {
                    $q2->where('categories.id', $request->category);
                });
            });
        }
        
        // Filtrage par sous-catégorie de service
        if ($request->has('subcategory')) {
            $query->whereHas('services', function($q) use ($request) {
                $q->whereHas('categories', function($q2) use ($request) {
                    $q2->where('categories.id', $request->subcategory);
                });
            });
        }
        
        // Filtrage par ville - improved case-insensitive search
        if ($request->has('city') && !empty($request->city)) {
            $city = trim($request->city);
            $query->where(function($q) use ($city) {
                $q->where('city', 'like', '%' . $city . '%')
                  ->orWhere('postal_code', 'like', '%' . $city . '%')
                  ->orWhere('address', 'like', '%' . $city . '%');
            });
        }
        
        $prestataires = $query->paginate(12);
        
        // Récupérer les catégories pour le filtre
        $categories = Category::orderBy('name')->get();
        
        // Récupérer les sous-catégories si une catégorie est sélectionnée
        $subcategories = collect();
        if ($request->has('category') && $request->category) {
            $subcategories = Category::where('parent_id', $request->category)->orderBy('name')->get();
        }
        
        return view('prestataires.index', compact('prestataires', 'categories', 'subcategories'));
    }

    /**
     * Affiche le profil public d'un prestataire.
     *
     * @param  \App\Models\Prestataire  $prestataire
     * @return \Illuminate\View\View
     */
    public function show(Prestataire $prestataire)
    {
        // Vérifier que le prestataire est approuvé
        if (!$prestataire->is_approved) {
            abort(404);
        }
        
        // Load all necessary relationships for the show view
        $prestataire->load([
            'user', 
            'services' => function($query) {
                $query->latest();
            },
            'videos' => function($query) {
                $query->latest();
            },
            'reviews' => function($query) {
                $query->with(['client'])->latest();
            },
            'equipments' => function($query) {
                $query->where('status', 'active')
                      ->where('is_available', true)
                      ->latest();
            }
        ]);
        
        // Récupérer les services similaires d'autres prestataires
        // Obtenir d'abord les IDs des services du prestataire
        $serviceIds = $prestataire->services->pluck('id')->toArray();
        
        // Obtenir les IDs des catégories associées à ces services
        $categoryIds = \DB::table('service_category')
            ->whereIn('service_id', $serviceIds)
            ->pluck('category_id')
            ->unique()
            ->toArray();
            
        $similarServices = Service::whereHas('categories', function($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
        ->where('prestataire_id', '!=', $prestataire->id)
        ->with('prestataire.user')
        ->take(4)
        ->get();
        
        return view('prestataires.show', compact('prestataire', 'similarServices'));
    }


}