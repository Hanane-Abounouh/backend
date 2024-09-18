<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Inscrit un nouvel utilisateur et retourne un token d'accès.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => 2, // Rôle par défaut pour le Créateur de Projet
        ]);

       

        return response()->json([
            'message' => 'Inscription réussie!',
            'user' => $user,
          
        ]);
    }

    /**
     * Authentifie un utilisateur et retourne un token d'accès.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Les informations d\'identification sont incorrectes.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Récupère le rôle de l'utilisateur
        $role = $user->role;

        return response()->json([
            'message' => 'Connexion réussie!',
            'user' => [
                'email' => $user->email,
                'role' => [
                    'id' => $role ? $role->id : null,
                    'nom' => $role ? $role->nom : 'Créateur', // Défaut à 'Créateur' si le rôle n'est pas trouvé
                ],
            ],
            'token' => $token,
        ]);
    }

    /**
     * Déconnecte l'utilisateur authentifié et révoque ses tokens.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie!',
        ]);
    }
}
