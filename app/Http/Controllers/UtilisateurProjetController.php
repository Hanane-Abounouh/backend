<?php

namespace App\Http\Controllers;

use App\Models\UtilisateurProjet;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilisateurProjetController extends Controller
{
    // Ajouter un utilisateur à un projet avec un rôle
    public function store(Request $request, $projetId)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Récupérer le projet
        $projet = Projet::findOrFail($projetId);

        // Ajouter l'utilisateur au projet avec le rôle spécifié
        $utilisateurProjet = UtilisateurProjet::updateOrCreate(
            ['utilisateur_id' => $request->utilisateur_id, 'projet_id' => $projet->id],
            ['role_id' => $request->role_id, 'invitation_acceptee' => false]
        );

        return response()->json(['message' => 'Utilisateur ajouté au projet avec succès', 'utilisateur_projet' => $utilisateurProjet], 201);
    }

    // Accepter une invitation à un projet
    public function accepterInvitation($projetId)
    {
        $utilisateurProjet = UtilisateurProjet::where('projet_id', $projetId)
            ->where('utilisateur_id', Auth::id())
            ->firstOrFail();

        if (!$utilisateurProjet->invitation_acceptee) {
            $utilisateurProjet->invitation_acceptee = true;
            $utilisateurProjet->save();

            return redirect()->route('projets.show', ['projet' => $projetId])
                             ->with('success', 'Invitation acceptée avec succès.');
        } else {
            return redirect()->route('projets.show', ['projet' => $projetId])
                             ->with('info', 'Vous avez déjà accepté cette invitation.');
        }
    }

    // Liste des utilisateurs d'un projet
    public function listUtilisateurs($projetId)
    {
        $projet = Projet::findOrFail($projetId);

        $utilisateurs = $projet->utilisateurs()->withPivot('role_id', 'invitation_acceptee')->get();

        return response()->json(['utilisateurs' => $utilisateurs]);
    }

    // Supprimer un utilisateur d'un projet
    public function deleteUtilisateur(Request $request, $projetId, $utilisateurId)
    {
        $utilisateurProjet = UtilisateurProjet::where('projet_id', $projetId)
            ->where('utilisateur_id', $utilisateurId)
            ->firstOrFail();

        $utilisateurProjet->delete();

        return response()->json(['message' => 'Utilisateur supprimé du projet avec succès'], 200);
    }
}
