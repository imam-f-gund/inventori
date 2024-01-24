<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HistoryTransaksion;

class MeController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();

        $sum = HistoryTransaksion::where('status','pending')->sum('status');

        return response()->json([
            'email' => $user->email,
            'first_name' => $user->first_name,
            'id' => $user->id,
            'last_name' => $user->last_name,
            'role_id' => $user->role_id,
            'updated_at' => $user->updated_at,
            'username' => $user->username,
            'infoRequest' => $sum
        ], 200);
    }
}