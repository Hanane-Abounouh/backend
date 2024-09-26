<?php

namespace App\Http\Controllers;

use App\Models\Tache; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskNotification;
use App\Events\AssignedTask;

class TacheController extends Controller 
{
    public function index()
    {
        return response()->json(Tache::all());
    }

    public function tasksByProject($projetId)
    {
        $taches = Tache::where('projet_id', $projetId)->get();
        return response()->json($taches);
    }

    public function show(Tache $tache)
    {
        return response()->json($tache);
    }

    public function store(Request $request)
    {
        $this->validateTache($request);
        $tache = Tache::create(array_merge($request->all(), ['cree_par' => auth()->id()]));

        // Notifier l'utilisateur assigné à la tâche, s'il y en a un
        $this->notifierUtilisateur($request->assigne_a, $tache);

        return response()->json(['message' => 'Tâche créée avec succès', 'tache' => $tache], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validateTache($request);
        $tache = Tache::findOrFail($id);
        $tache->update($request->only([
            'titre', 'description', 'date_limite', 'statut', 'priorite', 'assigne_a' // Ajout de assigne_a
        ]));

        // Notifier l'utilisateur assigné à la tâche, s'il y en a un
        $this->notifierUtilisateur($request->assigne_a, $tache);

        return response()->json(['message' => 'Tâche mise à jour avec succès', 'tache' => $tache], 200);
    }

    // Supprimer une tâche spécifique
    public function destroy($id)
    {
        $tache = Tache::findOrFail($id);
        $tache->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès'], 200);
    }
    
    // Assigner une tâche à un utilisateur
    public function AssignedTask(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
        ]);

        $tache = Tache::findOrFail($id);
        $tache->assigne_a = $request->user_id; 
        $tache->save();

        $this->notifierUtilisateur($tache->assigne_a, $tache);

        return response()->json(['message' => 'Tâche assignée avec succès', 'tache' => $tache], 200);
    }

    // Méthodes privées pour validation
    private function validateTache(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_limite' => 'nullable|date',
            'statut' => 'required|string|in:backlog,a faire,en cours,termine,bloque',
            'priorite' => 'nullable|string|in:basse,moyenne,elevee', // Priorité optionnelle
            'assigne_a' => 'nullable|exists:users,id', 
        ]);
    }

    private function notifierUtilisateur($userId, Tache $tache)
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                Notification::send($user, new TaskNotification($tache));
                event(new AssignedTask($tache));
            }
        }
    }
}
