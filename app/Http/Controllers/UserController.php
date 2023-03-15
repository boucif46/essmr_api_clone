<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Guards\TokenGuard;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|phone|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone_number'=> $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('my-app-token')->accessToken;
      
        return response(['user' => $user, 'access_token' => $token]);
    }

    

    public function login(Request $request)
{
    $validatedData = $request->validate([
        'phone_number' => 'required|numeric',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('phone_number', 'password');

    if (Auth::guard('client')->attempt($credentials)) {
        $user = Auth::guard('client')->user();
        $token = $user->createToken('my-app-token')->accessToken;

        return response(['user' => $user, 'access_token' => $token]);
    }

    return response(['error' => 'Invalid credentials'], 401);
}


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }
    

    public function update(Request $request)
    {

        
    // Check if the user has a valid access token

    $user = $request->user();

    if (!$user || !$request->user()->token() ) {
        return response(['error' => 'Unauthorized'], 401);
    }

        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255|unique:users,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user->name = $request->input('name', $user->name);
        $user->last_name = $request->input('last_name', $user->last_name);
        $user->phone_number = $request->input('email', $user->phone_number);
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response(['user' => $user]);
    }
}
