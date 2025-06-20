<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
           
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }
    public function getProfile()
    {
        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }

    
}
