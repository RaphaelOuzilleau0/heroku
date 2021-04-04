<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\OpenApi(
 *    security={{"bearerAuth": {}}}
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *     )
 * )
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::paginate(20));
    }

    /**
    *@OA\Post(
    *   path="/api/users",
    *   tags={"User"},
    *   summary="Add a new user",
    *   @OA\RequestBody(
    *       @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                   @OA\Property(
    *                       property="login",
    *                       type="string"
    *                   ),
    *                   @OA\Property(
    *                      property="password",
    *                      type="string"
    *                   ),
    *                   @OA\Property(
    *                      property="email",
    *                      type="string"
    *                   ),
    *                   @OA\Property(
    *                      property="first_name",
    *                      type="string"
    *                    ),
    *                   @OA\Property(
    *                       property="last_name",
    *                       type="string"
    *                   ),
    *                   @OA\Property(
    *                       property="role_id",
    *                       type="integer"
    *                 ),
    *                 example={"login": "username", "password": "password", "email": "your@email.com", "first_name": "name", "last_name": "name", "role_id": 0}
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=405,
    *         description="Invalid input",
    *     )
    * )
    */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $user = new User;
        $user->login = $data['login'];
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->role_id = $data['role_id'];
        $user->timestamps = false;
            
        $user->saveOrFail();

        $token = $user->createToken($user->login);
        return response(['token' => $token->plainTextToken], 201);
    }

    /**
    *@OA\Post(
    *   path="/api/user",
    *   tags={"User"},
    *   summary="Show the informations of the connected user.",
    *   security={{ "bearerAuth":{} }},
    *   @OA\Response(
    *       response=200,
    *       description="OK"
    *   )
    *)
    */
    public function show()
    {
        if (Auth::check()) {
            return Auth::user();
        }
    }

    /**
    *@OA\Put(
    *   path="/api/user",
    *   tags={"User"},
    *   summary="Update a user",
    *   @OA\RequestBody(
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="login",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="password",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="email",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="first_name",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="last_name",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="role_id",
    *                   type="integer"
    *               ),
    *               example={"login": "username", "password": "password", "email": "your@email.com", "first_name": "name", "last_name": "name", "role_id": 0}
    *           )
    *       )
    *   ),
    *   security={{ "bearerAuth":{} }},
    *   @OA\Response(
    *       response=405,
    *       description="Invalid input",
    *   )
    *)
    */
    public function update(Request $request)
    {
        $data = $request->input();
        $user = Auth::user();
        if (isset($data['login'])) {
            $user->login = $data['login'];
        }
        if (isset($data['password'])) {
            $user->password =  Hash::make($data['password']);
        }
        if (isset($data['email'])) {
            $user->email =  $data['email'];
        }
        if (isset($data['first_name'])) {
            $user->first_name =  $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $user->last_name = $data['last_name'];
        }
        if (isset($data['role_id'])) {
            $user->role_id =  $data['role_id'];
        }
        $user->save();
        return response(Auth::user(), 201);
    }
}