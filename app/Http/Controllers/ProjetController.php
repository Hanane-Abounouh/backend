<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\UtilisateurProjet;
use App\Models\Role;
use App\Models\User;
use App\Notifications\InvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ProjetController extends Controller
{
    // Créer un projet
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        // Création du projet
        $projet = Projet::create([
            'name' => $request->name,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'créé_par' => Auth::id(),
        ]);

        // Ajouter l'utilisateur comme "Créateur" avec role_id 2
        UtilisateurProjet::updateOrCreate(
            ['utilisateur_id' => Auth::id(), 'projet_id' => $projet->id],
            ['role_id' => 2] // 2 est l'ID du rôle "Créateur"
        );

        return response()->json(['message' => 'Projet créé avec succès', 'projet' => $projet], 201);
    }

    // Lister tous les projets
    public function index()
    {
        $projets = Projet::all();
        return response()->json($projets, 200);
    }

    // Voir un projet spécifique
    public function show($id)
    {
        $projet = Projet::findOrFail($id);
        return response()->json($projet, 200);
    }

    // Mettre à jour un projet
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $projet = Projet::findOrFail($id);

        $projet->update([
            'name' => $request->name,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return response()->json(['message' => 'Projet mis à jour avec succès', 'projet' => $projet], 200);
    }

    // Supprimer un projet
    public function destroy($id)
    {
        $projet = Projet::findOrFail($id);

        // Supprimer le projet
        $projet->delete();

        return response()->json(['message' => 'Projet supprimé avec succès'], 200);
    }

    // Inviter un utilisateur à un projet
    public function inviteUser(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $projet = Projet::findOrFail($projectId);
        $user = User::findOrFail($request->user_id);

        // Inviter l'utilisateur avec le rôle "Invité" avec role_id 3
        UtilisateurProjet::updateOrCreate(
            ['utilisateur_id' => $user->id, 'projet_id' => $projet->id],
            ['role_id' => 3] // 3 est l'ID du rôle "Invité"
        );

        // Envoyer une notification à l'utilisateur
        $user->notify(new InvitationNotification($projet));

        return response()->json(['message' => 'Utilisateur invité avec succès'], 200);
    }
}
