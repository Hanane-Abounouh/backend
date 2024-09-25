<?php

namespace App\Http\Controllers;

use App\Models\Fichier;
use Illuminate\Http\Request;

class FichierController extends Controller
{
    public function index()
    {
        return Fichier::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'chemin' => 'required|string|max:255',
            'version' => 'integer',
            'projet_id' => 'required|exists:projets,id',
            'téléversé_par' => 'required|exists:users,id',
        ]);

        return Fichier::create($validated);
    }

    public function show(Fichier $fichier)
    {
        return $fichier;
    }

    public function update(Request $request, Fichier $fichier)
    {
        $validated = $request->validate([
            'nom' => 'string|max:255',
            'chemin' => 'string|max:255',
            'version' => 'integer',
            'projet_id' => 'exists:projets,id',
            'téléversé_par' => 'exists:users,id',
        ]);

        $fichier->update($validated);

        return $fichier;
    }

    public function destroy(Fichier $fichier)
    {
        $fichier->delete();
        return response()->noContent();
    }
}
