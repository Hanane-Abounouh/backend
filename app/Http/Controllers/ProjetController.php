<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\UtilisateurProjet;
use App\Models\User;
use App\Notifications\InvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ProjetInvite; 

class ProjetController extends Controller
{
    // Créer un projet
    public function store(Request $request)
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
    public function index()
    {
        $allProjets = Projet::with('utilisateur')->get(); // Chargement des utilisateurs
        return response()->json($allProjets, 200);
    }
    
    public function userProjets()
    {
        $userProjets = Projet::with('utilisateur')
            ->where('créé_par', Auth::id())
            ->get();
    
        return response()->json($userProjets, 200);
    }
    
    // Dans votre ProjetController
public function utilisateurs($projetId)
{
    $projet = Projet::findOrFail($projetId);
    $utilisateurs = $projet->utilisateurs()->withPivot('role_id', 'invitation_acceptee')->get();

    return response()->json(['utilisateurs' => $utilisateurs]);
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


    public function inviteUser(Request $request, $projectId)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $projet = Projet::findOrFail($projectId);
    $user = User::where('email', $request->email)->firstOrFail();

    UtilisateurProjet::updateOrCreate(
        ['utilisateur_id' => $user->id, 'projet_id' => $projet->id],
        ['role_id' => 3] // Assurez-vous que c'est le bon rôle
    );

    // Diffuser l'événement
    broadcast(new ProjetInvité($projet, $user->id));

    // Envoyer la notification
    $user->notify(new InvitationNotification($projet));

    return response()->json(['message' => 'Utilisateur invité avec succès'], 200);
}

    
  
  

    
}
