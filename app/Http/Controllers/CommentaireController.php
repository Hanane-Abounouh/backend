<?php


namespace App\Http\Controllers;

use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    // Créer un nouveau commentaire
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'contenu' => 'required|string',
            'tâche_id' => 'required|exists:taches,id', // Assurez-vous que le nom est correct
        ]);
    
        // Création du commentaire
        $commentaire = Commentaire::create([
            'contenu' => $request->contenu,
            'tâche_id' => $request->tâche_id, // Vérifiez que c'est bien 'tâche_id' en base de données
            'utilisateur_id' => Auth::id(), // L'utilisateur connecté
        ]);
    
        // Retourner une réponse JSON
        return response()->json(['message' => 'Commentaire ajouté avec succès.', 'commentaire' => $commentaire], 201);
    }
    
    
    // Lister les commentaires d'une tâche
    public function index($tâcheId)
    {
        $commentaires = Commentaire::where('tâche_id', $tâcheId)->with('utilisateur')->get();

        return response()->json(['commentaires' => $commentaires], 200);
    }

    // Supprimer un commentaire
    public function destroy($id)
    {
        $commentaire = Commentaire::findOrFail($id);
        
        // Vérifiez si l'utilisateur a le droit de supprimer le commentaire (si nécessaire)
        if ($commentaire->utilisateur_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès.'], 200);
    }
}
