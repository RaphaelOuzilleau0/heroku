<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\CreateUserRequest;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

       /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"user"},
     *     operationId="storeUser",
     *     summary="Add a new user",
     *     description="",
     *     @OA\RequestBody(
     *         description="User object that needs to be added",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *         description="User object that needs to be added",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/xml",
     *             @OA\Schema(ref="#/components/schemas/User")
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
        $data = $request->input();
			try{
				$user = new User;
                $user->login = $data['login'];
                $user->password = $data['password'];
                $user->email = $data['email'];
                $user->first_name = $data['first_name'];
                $user->last_name = $data['last_name'];
                $user->role_id = $data['role_id'];
                $user->timestamps = false;
				
				$user->saveOrFail();
                return response('Succès!', 201);
			}
			catch(Exception $e){
				//return redirect('insert')->with('failed',"operation failed");
			}   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
