<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(401, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'phone' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string'
        ]);

        $gym = new Gym([
            'name' => $request->name,
            'description' => $request->description,
            'phone' => $request->phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        $gym->save();

        return response()->json([
            'message' => 'Successfully created gym!'
        ], 201);
    }

    /**
     * Get all gyms.
     */
    public function get_all(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(401, 'Unauthorized');
        }

        return response()->json(Gym::all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(401, 'Unauthorized');
        }
        $data = $request->all();

        if (!$gym = Gym::find($id)) {
            abort(404, 'Gym not found');
        }

        $gym->update($data);

        return response()->json([
            'message' => 'Successfully updated gym!',
            'gym' => $gym
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(401, 'Unauthorized');
        }
        $gym = Gym::find($id);

        $gym->delete();
    }
}
