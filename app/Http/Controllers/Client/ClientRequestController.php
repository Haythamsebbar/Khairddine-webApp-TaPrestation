<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string|in:location,annonces,developpement_web,design,marketing,autre',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'budget' => 'nullable|string|in:moins_500,500_1000,1000_2500,2500_5000,plus_5000,a_discuter',
            'location' => 'nullable|string|max:255',
            'due_date' => 'nullable|date|after:today',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240'
        ], [
            'service_type.required' => 'Veuillez sélectionner le type de service recherché.',
            'title.required' => 'Le titre de la demande est obligatoire.',
            'description.required' => 'La description détaillée est obligatoire.',
            'description.min' => 'La description doit contenir au moins 50 caractères.',
            'due_date.after' => 'La date limite doit être postérieure à aujourd\'hui.',
            'attachments.*.mimes' => 'Les fichiers doivent être au format PDF, DOC, DOCX, JPG, JPEG, PNG ou GIF.',
            'attachments.*.max' => 'Chaque fichier ne doit pas dépasser 10 MB.'
        ]);

        try {
            // Créer la demande
            $clientRequest = \App\Models\ClientRequest::create([
                'client_id' => auth()->user()->client->id,
                'service_type' => $request->service_type,
                'title' => $request->title,
                'description' => $request->description,
                'budget' => $request->budget,
                'location' => $request->location,
                'due_date' => $request->due_date,
                'status' => 'pending'
            ]);

            // Gérer les fichiers joints
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('client-requests/' . $clientRequest->id, 'public');
                    
                    \App\Models\ClientRequestAttachment::create([
                        'client_request_id' => $clientRequest->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]);
                }
            }

            return redirect()->route('client.dashboard')
                           ->with('success', 'Votre demande de prestation a été publiée avec succès ! Les prestataires vont pouvoir vous envoyer leurs propositions.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Une erreur est survenue lors de la publication de votre demande. Veuillez réessayer.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}