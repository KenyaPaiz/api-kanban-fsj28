<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 * name="authentication",
 * description="User login and session management"
 * )
 */
class LoginController extends Controller
{
    //metodo para iniciar sesion y obtener el token
    /**
     * @OA\Post(
     * path="/login",
     * tags={"authentication"},
     * summary="User login and token generation",
     * description="Authenticates the user using email and password and returns a JWT token for subsequent requests.",
     * @OA\RequestBody(
     * required=true,
     * description="User credentials",
     * @OA\JsonContent(
     * required={"email", "password"},
     * @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     * @OA\Property(property="password", type="string", format="password", example="secretpassword")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Login successful. Token returned.",
     * @OA\JsonContent(
     * @OA\Property(property="user", type="string", example="Jane Doe"),
     * @OA\Property(property="email", type="string", format="email", example="jane.doe@app.com"),
     * @OA\Property(property="access_token", type="string", description="JWT Bearer token"),
     * @OA\Property(property="token_type", type="string", example="Bearer")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Invalid credentials",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Invalid credentials")
     * )
     * )
     * )
     */
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->where('password', $password)->first(); //{}
        if($user){
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => $user->name,
                'email' => $user->email,
                'access_token' => $token, 
                'token_type' => 'Bearer'
            ], 200); 
        }else{
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    //metodo para cerrar sesion y eliminar el token
    /**
     * @OA\Post(
     * path="/logout",
     * tags={"authentication"},
     * summary="User logout and token revocation",
     * description="Revokes all tokens for the authenticated user, effectively logging them out of all devices. Requires authentication.",
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Logged out successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Logged out successfully")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized (Missing or invalid token)",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     * )
     */
    public function logout(Request $request){
        //$request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
