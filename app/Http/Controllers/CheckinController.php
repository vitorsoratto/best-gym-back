<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function checkin(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'gym_id' => 'required|integer'
        ]);

        $data = $request->all();

        $hasCheckin = Checkin::where(['user_id' => $user->id])
            ->whereDate('created_at', Carbon::today())
            ->first();
        if (isset($hasCheckin) && !empty($hasCheckin)) {
            abort(400, 'Only one checkin is permmited per day.');
        }

        $checkin = new Checkin([
            'gym_id' => $data['gym_id'],
            'user_id' => $user->id,
        ]);

        $checkin->save();

        return response()->json([
            'message' => 'Successfully checked in!',
            'gym' => $checkin->gym,
            'user' => $checkin->user,
        ], 201);
    }

    public function checkin_history(Request $request)
    {
        $user = $request->user();

        $checkins = Checkin::where(['user_id' => $user->id])->get();

        $response = [];
        if (!empty($checkins)) {
            foreach ($checkins as $checkin) {
                $response[] = [
                    'gym' => $checkin->gym->name,
                    'created_at' => $checkin->created_at
                ];
            }
        }


        return response()->json($response, 200);
    }
}
