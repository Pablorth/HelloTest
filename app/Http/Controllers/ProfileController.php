<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Store a newly created profile in storage.
     *
     * @param  \App\Http\Requests\StoreProfileRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProfileRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('profiles', 'public');
        $profile = Profile::create($data);
        return response()->json($profile, 201);
    }

    /**
     * Display a listing of active profiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $profiles = Profile::where('statut', 'actif')
            ->select('id', 'nom', 'prenom', 'image')
            ->get();
        return response()->json($profiles);
    }

    /**
     * Update the specified profile in storage.
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        // Implémentez la logique de mise à jour ici
    }

    /**
     * Remove the specified profile from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();
        return response()->json(null, 204);
    }
}
