<?php

namespace App\Http\Controllers;

use App\Models\Tâche;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TâcheNotification;
use App\Events\TâcheAssignée;

class TâcheController extends Controller
{
    // Afficher une liste de toutes les tâches
    public function index()
    {
        return response()->json(Tâche::all());
    }

    // Afficher les tâches d'un projet spécifique
    public function tasksByProject($projetId)
    {
        $tâches = Tâche::where('projet_id', $projetId)->get();
        return response()->json($tâches);
    }

    // Afficher une tâche spécifique
    public function show(Tâche $tâche)
    {
        return response()->json($tâche);
    }

    // Créer une nouvelle tâche
    public function store(Request $request)
    {
        $this->validateTâche($request);

        // Créer la tâche
        $tâche = Tâche::create(array_merge($request->all(), ['créé_par' => auth()->id()]));

        // Notifier l'utilisateur assigné à la tâche, s'il y en a un
        $this->notifierUtilisateur($request->assigné_a, $tâche);

        return response()->json(['message' => 'Tâche créée avec succès', 'tâche' => $tâche], 201);
    }

    // Mettre à jour une tâche existante
    public function update(Request $request, $id)
    {
        $this->validateTâche($request);
        $tâche = Tâche::findOrFail($id);

        // Mettre à jour la tâche avec les données du requête
        $tâche->update($request->only([
            'titre', 'description', 'date_limite', 'statut', 'priorité', 'assigné_a' // Ajout de assigné_a
        ]));

        // Notifier l'utilisateur assigné à la tâche, s'il y en a un
        $this->notifierUtilisateur($request->assigné_a, $tâche);

        return response()->json(['message' => 'Tâche mise à jour avec succès', 'tâche' => $tâche], 200);
    }

    // Supprimer une tâche spécifique
    public function destroy($id)
    {
        // Trouver la tâche par son ID, ou lancer une exception si elle n'existe pas
        $tâche = Tâche::findOrFail($id);
        $tâche->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès'], 200);
    }
    
    // Assigner une tâche à un utilisateur
    public function assignerTâche(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
        ]);

        $tâche = Tâche::findOrFail($id);
        $tâche->assigné_a = $request->user_id; // Assurez-vous que votre modèle Tâche a cette colonne
        $tâche->save();

        // Notifier l'utilisateur assigné
        $this->notifierUtilisateur($tâche->assigné_a, $tâche);

        return response()->json(['message' => 'Tâche assignée avec succès', 'tâche' => $tâche], 200);
    }

    // Méthodes privées pour validation
    private function validateTâche(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_limite' => 'nullable|date',
            'statut' => 'required|string|in:backlog,à faire,en cours,terminé,bloqué',
            'priorité' => 'required|string|in:basse,moyenne,élevée',
            'assigné_a' => 'nullable|exists:users,id', // Assurez-vous que ce champ existe dans le modèle
        ]);
    }

    // Méthode pour notifier l'utilisateur assigné à la tâche
    private function notifierUtilisateur($userId, Tâche $tâche)
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                Notification::send($user, new TâcheNotification($tâche));
                event(new TâcheAssignée($tâche));
            }
        }
    }
}
