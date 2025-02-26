<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profils = Profil::where('statut', 'actif')->select('id', 'nom', 'prénom', 'image')->get();
        return response()->json($profils);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        $profil = Profil::create($validated);

        return response()->json($profil, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilRequest $request, $id)
    {
        $profil = Profil::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

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
