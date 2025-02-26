Création des Modèles et Migrations : 
php artisan make:model Administrateur -m
php artisan make:model Profil -m

Définition des migrations:

    public function up(): void
    {
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

   public function up(): void
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prénom');
            $table->string('image');
            $table->enum('statut', ['inactif', 'en attente', 'actif']);
            $table->timestamps();
        });
    }

Création du contrôleur ProfilController :

php artisan make:controller ProfilController --api

use App\Http\Controllers\ProfilController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profils', [ProfilController::class, 'store']);
    Route::put('/profils/{id}', [ProfilController::class, 'update']);
    Route::delete('/profils/{id}', [ProfilController::class, 'destroy']);
});

Route::get('/profils', [ProfilController::class, 'index']);

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $admin = \App\Models\Administrateur::where('email', $request->email)->first();
    if (! $admin || ! \Hash::check($request->password, $admin->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $admin->createToken('Admin Access')->plainTextToken;

    return response()->json(['token' => $token]);
});

utislisation de sanctum pour la gestion de l'identification et la protection des routes par token.

CRUD des profils dans ProfilController. 

Création de: 
App\Http\Requests\StoreProfilRequest et 
App\Http\Requests\UpdateProfilRequest afin de valider les données.

création d un seeder pour avoir un admin.

Via postman:
POST http://127.0.0.1:8001/api/sanctum/token?email=admin@example.fr&password=password
résultat: MY_SECRET_TOKEN

POST http://127.0.0.1:8001/api/profils
(ajouter Authorization type Bearer Token:MY_SECRET_TOKEN)
résultat: création d'un profil avec la data souhaitée.

Pareil pour le update et destroy.

Utilisation de l'IA pour génerer les cruds et les validators afin de gagner du temps sur des actions basiques.

Temps total : 2h
