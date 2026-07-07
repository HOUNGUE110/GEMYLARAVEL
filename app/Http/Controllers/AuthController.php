<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        // 1. Validation stricte des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Création de l'utilisateur en base de données
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password), // Chiffrement Bcrypt
        ]);

        // 3. Génération du jeton (Token) Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retour synchronisé avec React ('token' au lieu de 'access_token')
        return response()->json([
            'message' => 'Utilisateur créé avec succès !',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    /**
     * Connexion de l'utilisateur
     */
    public function login(Request $request)
    {
        // 1. Validation de la tentative
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Vérification de l'existence de l'e-mail
        $user = User::where('email', $request->email)->first();

        // 3. Vérification du mot de passe haché
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants de connexion invalides.'
            ], 401); // Correction du code : 401 Unauthorized (au lieu de 410)
        }

        // 4. Régénération d'un nouveau jeton valide
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retour synchronisé avec React ('token' au lieu de 'access_token')
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    /**
     * Déconnexion (Révocation du jeton)
     */
    public function logout(Request $request)
    {
        // Supprime le token actuellement utilisé par l'appareil connecté
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie et jeton révoqué.'
        ], 200);
    }
}