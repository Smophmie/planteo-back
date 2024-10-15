<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for managing users"
 * )
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Gerard Dupont"),
 *     @OA\Property(property="email", type="string", example="gdupont@example.com"),
 *     @OA\Property(property="city", type="string", example="Paris"),
 *     @OA\Property(property="is_admin", type="boolean", example=false)
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="Password123!"),
     *             @OA\Property(property="password_confirmation", type="string", example="Password123!"),
     *             @OA\Property(property="city", type="string", example="Paris")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Le champ email est requis.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La création de compte n'a pas fonctionné!")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required', 
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',                
                'confirmed'
            ],
            'password_confirmation' => 'required',
            'city' => 'required',
        ]);
        try {
            $user = User::create($validatedData);
    
            return response()->json([
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'La création de compte n\'a pas fonctionné!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="Password123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Utilisateur connecté."),
     *             @OA\Property(property="token", type="string", example="token"),
     *             @OA\Property(property="is_admin", type="boolean", example=true),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="city", type="string", example="Paris")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="L'e-mail ou le mot de passe est incorrect.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur serveur.")
     *         )
     *     )
     * )
     */
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
                    'message' => 'Erreur de validation',
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
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'is_admin' => $user->is_admin,
                'name'=> $user->name,
                'city' => $user->city
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID of the user"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="city", type="string", example="Paris")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="L'utilisateur a été mis à jour")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error message")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'city' => 'required'
          ]);
          $user = User::find($id);
          $user->update($request->all());
          return "L'utilisateur a été mis à jour";
    }

    /**
     * @OA\Get(
     *     path="/api/connectedUser",
     *     summary="Get the currently connected user",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Connected user details",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function connectedUser()
    {
        $user = Auth::user();
        return $user;
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout a user",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Utilisateur non déconnecté")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Utilisateur non déconnecté',
        ], 401);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{id}/toggle-admin",
     *     summary="Toggle admin status of a user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User admin status toggled",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Non autorisé")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function toggleAdminStatus($id)
    {
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        $user = User::find($id);

        $user->is_admin = !$user->is_admin;

        $user->save();

        return response()->json($user);
    }

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Get the favorites of the connected user",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="List of favorite plants",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Plant"))
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getFavorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->get();
        return $favorites;
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID of the user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="L'utilisateur a été supprimé")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return 'L\'utilisateur a été supprimé';
    }
}
