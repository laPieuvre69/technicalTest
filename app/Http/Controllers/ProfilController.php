<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Define the base fields to select
        $fields = ['id', 'nom', 'prénom', 'image'];

        // If the user is authenticated, add the 'statut' field
        if ($request->user('sanctum')) {
            $fields[] = 'statut';
        }

        // Retrieve profiles with the selected fields
        $profils = Profil::where('statut', 'actif')
            ->select($fields)
            ->get();

        return response()->json($profils);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilRequest $request)
    {
        $validated = $request->validated();
        // Store the file and update the validated data
        $this->storeFile($request, $validated);

        $profil = Profil::create($validated);

        return response()->json($profil, 201);
    }

    /**
     * Store the uploaded file and update the validated data.
     */
    public function storeFile(Request $request, &$validated)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Validate the file
            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file upload.'], 400);
            }

            try {
                $imagePath = $file->store('images', 'public');
                $validated['image'] = $imagePath;
            } catch (\Exception $e) {
                return response()->json(['error' => 'File upload failed.', 'message' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implementation for showing a specific profile
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilRequest $request, $id)
    {
        $profil = Profil::findOrFail($id);

        $validated = $request->validated();

        // Store the file and update the validated data
        $this->storeFile($request, $validated);

        $profil->update($validated);

        return response()->json($profil);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $profil = Profil::findOrFail($id);
        $profil->delete();

        return response()->json(null, 204);
    }
}
