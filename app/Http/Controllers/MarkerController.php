public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'titre' => 'required|string|max:100',
        'description' => 'required|string',
        'categorie' => 'required|string',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'contact' => 'required|string|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Création manuelle et sécurisée sans dépendre uniquement de la relation ou du fillable
    $marker = new Marker();
    $marker->titre = $request->titre;
    $marker->description = $request->description;
    $marker->categorie = $request->categorie;
    $marker->latitude = $request->latitude;
    $marker->longitude = $request->longitude;
    $marker->contact = $request->contact;
    $marker->user_id = $request->user()->id; // On lie l'ID de l'utilisateur authentifié par le Token
    $marker->save();

    return response()->json([
        'message' => 'Marqueur ajouté avec succès !',
        'marker' => $marker->load('user:id,nom,prenom')
    ], 201);
}