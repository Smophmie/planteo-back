<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function show(string $id)
    {
        $user = User::find($id);
        return $user;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required', 
                'email',
                'unique:users,email'
            ],
            'is_admin' => [
                'required'
            ],
            'password' => [
                'required', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',                
                'confirmed'
            ],
        ]);
        $user = User::create($request->all());
        $token = $user->createToken("API TOKEN")->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required',
            'is_admin' => 'required',
            'password' => 'required'
        ]);
        User::create($request->all());
        return "Utilisateur créé avec succès";
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => "L'e-mail ou le mot de passe est incorrect.",
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if(auth('sanctum')->check()){
                auth()->user()->tokens()->delete();
             }

            return response()->json([
                'status' => true,
                'message' => 'Utilisateur connecté.',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'is_admin' => 'required',
            'email' => 'required',
            'password' => 'required'
          ]);
          $user = User::find($id);
          $user->update($request->all());
          return "L'utilisateur a été mis à jour";
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return 'L\'utilisateur a été supprimé';
    }
}
