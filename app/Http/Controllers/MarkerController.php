public function store(Request $request)
{
    // 1. Vérification que l'utilisateur est bien authentifié via Sanctum
    $user = $request->user();
    
    if (!$user) {
        return response()->json([
            'message' => 'Utilisateur non authentifié. Veuillez vous reconnecter.'
        ], 401);
    }

    // 2. Validation des données du formulaire
    $validator = Validator::make($request->all(), [
        'titre'       => 'required|string|max:100',
        'description' => 'required|string',
        'categorie'   => 'required|string',
        'latitude'    => 'required|numeric',
        'longitude'   => 'required|numeric',
        'contact'     => 'required|string|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // 3. Enregistrement sécurisé en base de données
    $marker = new Marker();
    $marker->titre = $request->titre;
    $marker->description = $request->description;
    $marker->categorie = $request->categorie;
    $marker->latitude = $request->latitude;
    $marker->longitude = $request->longitude;
    $marker->contact = $request->contact;
    $marker->user_id = $user->id; // Sécurisé : $user est garanti non null
    $marker->save();

    return response()->json([
        'message' => 'Marqueur ajouté avec succès !',
        'marker'  => $marker->load('user:id,nom,prenom')
    ], 201);
}