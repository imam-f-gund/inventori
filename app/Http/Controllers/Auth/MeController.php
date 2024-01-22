<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class MeController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();

        return response()->json($user, 200);
    }
}
