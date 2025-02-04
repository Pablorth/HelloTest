<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProfileController extends Controller
{

    /**
     * Display a listing of active profiles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $profiles = Profile::where('statut', 'actif')
            ->select('id', 'nom', 'prenom', 'image')
            ->get();

        return response()->json($profiles);
    }

    /**
     * Store a newly created profile in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'statut' => 'required|in:inactif,en attente,actif',
        ]);

        $imagePath = $request->file('image')->store('profiles', 'public');

        $profile = Profile::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'image' => $imagePath,
            'statut' => $request->statut,
        ]);

        return response()->json($profile, 201);
    }


    /**
     * Update the specified profile in storage.
     *
     * @param Request $request
     * @param Profile $profile
     * @return JsonResponse
     */
    public function update(Request $request, Profile $profile): JsonResponse
    {
        $request->validate([
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'statut' => 'in:inactif,en attente,actif',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($profile->image);
            $imagePath = $request->file('image')->store('profiles', 'public');
            $profile->image = $imagePath;
        }

        $profile->update($request->only(['nom', 'prenom', 'statut']));

        return response()->json($profile);
    }

    /**
     * Remove the specified profile from storage.
     *
     * @param Profile $profile
     * @return Response
     */
    public function destroy(Profile $profile): Response
    {
        Storage::disk('public')->delete($profile->image);
        $profile->delete();

        return response()->noContent();
    }
}
