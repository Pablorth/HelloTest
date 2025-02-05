<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Store a newly created profile in storage.
     *
     * @param  \App\Http\Requests\StoreProfileRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProfileRequest $request): JsonResponse
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
            ->get(['id', 'nom', 'prenom', 'image']);
        return response()->json($profiles);
    }

    /**
     * Update the specified profile in storage.
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        $profile->update($data);
        return response()->json($profile);
    }

    /**
     * Remove the specified profile from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Profile $profile): JsonResponse
    {
        $profile->delete();
        return response()->json(['message' => 'Profil supprimé']);
    }
}
