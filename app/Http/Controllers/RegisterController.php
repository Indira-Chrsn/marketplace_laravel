<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Http\Requests\RegisterPostRequest;
use Hash;

class RegisterController extends Controller
{
    /**
     * Create user
     * 
     * @param [string] name
     * @param [string] email
     * @param [string] password
     * @return [string] message
     */
    public function register(RegisterPostRequest $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:250',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|string|min:8'
        // ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Registration success.',
            'data' => $user

        ], 200);
    }
}
