<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole; // Use the UserRole enum
use App\Http\Controllers\Controller;    // Base controller
use App\Models\User;               // User model
use Illuminate\Http\Request;    // HTTP Request
use Illuminate\Support\Facades\Auth;    // Auth facade
use Illuminate\Support\Facades\Hash;    // Hashing passwords
use Illuminate\Support\Facades\Validator;   // Validation
use Illuminate\Validation\Rules\Enum;   // Enum validation rule

class AuthController extends Controller
{
    /**
     * Handle a registration request. This includes:
     * 1. Validating the incoming data
     * 2. Creating the user
     * 3. Creating a token for the user
     * 4. Returning the user and token
     */
    public function register(Request $request)
    {
        // 1. Validate the incoming data
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|max:255',    // Adjusted to userName
            'email' => 'required|string|email|max:255|unique:users',    // Unique email
            'password' => 'required|string|min:8|confirmed',    // Password confirmation
            'role' => ['required', new Enum(UserRole::class)],  // Role must be one of the defined enum values
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 means unprocessable entity
        }

        // 2. Create the new user
        $user = User::create([
            'userName' => $request->userName,   // Adjusted to userName
            'email' => $request->email,   // Adjusted to email
            'password' => Hash::make($request->password),   // Hash the password
            'role' => $request->role,   // Set the role
        ]);

        // 3. Create a token for the new user
        $token = $user->createToken('auth_token')->plainTextToken;  // Create a personal access token

        // 4. Return the user and the token
        return response()->json([
            'user' => $user,    // Return the user
            'access_token' => $token,   // Return the access token
            'token_type' => 'Bearer',   // Token type
        ], 201);
    }

    /**
     * Handle a login request. This includes:
     * 1. Validating the incoming data
     * 2. Attempting to authenticate the user
     * 3. Creating a token for the user
     * 4. Returning the user and token
     */
    public function login(Request $request)
    {
        // 1. Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',    // Email is required
            'password' => 'required|string',    // Password is required
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 means unprocessable entity
        }

        // 2. Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);   // 401 means unauthorized
        }

        // 3. Get the user and create a token
        $user = User::where('email', $request['email'])->firstOrFail(); // Retrieve the user by email
        $token = $user->createToken('auth_token')->plainTextToken;  // Create a personal access token

        // 4. Return the user and token
        return response()->json([
            'user' => $user,    // Return the user
            'access_token' => $token,   // Return the access token
            'token_type' => 'Bearer',   // Token type
        ]);
    }

    /**
     * Handle a logout request. This includes:
     * 1. Revoking the token that was used to authenticate the current request
     * 2. Returning a success message
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();   // Delete the current access token

        return response()->json(['message' => 'Successfully logged out']);  // Return a success message
    }
}
