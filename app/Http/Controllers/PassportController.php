<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    //
    /**
     * Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LoginGrant')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('LoginGrant')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'cannot authenticate user'], 404);
        }
    }

    /**
     * Returns Authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        if (auth()->user) {
            return response()->json(['user' => auth()->user()], 200);
        } else {
            return response()->json(['error' => 'cannot authenticate user' ], 404);
        }
    }
}
