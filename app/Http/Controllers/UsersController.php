<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        //  $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->get('email'))->first();
        if ($user && Hash::check($request->get('password'), $user->password)) {
            $user->generateUserToken();
            User::where('email', $request->get('email'))->update(['api_token' => $user->api_token]);;
            return response()->json(['status' => 'success', 'api_token' => $user->api_token]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|max:255',
            'name' => 'required|max:55',
            'password' => 'required|min:6',
        ]);

        try {
            $hashedPassword = app('hash')->make($request->get('password'));
            $user = new User();
            $user->email = $request->get('email');
            $user->name = $request->get('name');
            $user->password = $hashedPassword;
            $user->generateUserToken();
            $user->save();

            return $this->respondWithToken($user->api_token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
}
