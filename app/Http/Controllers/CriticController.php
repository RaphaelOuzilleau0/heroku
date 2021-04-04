<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critics;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCriticRequest;

class CriticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = CriticResource::collection(Critics::paginate(20));
        if (sizeof($response) == 0) {
            return response(["message"=>"Success but empty"], 204)->header('Content-Type', 'application/json');
        }
        return $response;
    }

    /**
    * @OA\Post(
    *     path="/api/critic",
    *     tags={"Critic"},
    *     summary="Add a new critic",
    *      @OA\Parameter(
    *          name="user_id",
    *          in="query",
    *          required=true,
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Parameter(
    *          name="film_id",
    *          in="query",
    *          required=true,
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Parameter(
    *          name="score",
    *          in="query",
    *          required=true,
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Parameter(
    *          name="comment",
    *          in="query",
    *          required=true,
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
    *   security={{ "bearerAuth":{} }},
    *   @OA\Response(
    *       response=405,
    *       description="Invalid input",
    *   )
    *)
    */
    public function store(CreateCriticRequest $request)
    {
        $data = $request->validated();
        $critic = new Critics;
        $critic->user_id = Auth::id();
        $critic->film_id = $data['film_id'];
        $critic->score = $data['score'];
        if (isset($data['comment'])) {
            $critic->comment = $data['comment'];
        }
            
        $critic->save();
        return response(["message"=>"Successfully Created Critic"], 201)->header('Content-Type', 'application/json');
    }

    /**
    * @OA\Get(
    *      path="/api/films/{id}/critics",
    *      operationId="getFilmCriticsById",
    *      tags={"Films"},
    *      summary="Get critics of a specific film",
    *      description="Returns film's critics",
    *      @OA\Parameter(
    *          name="id",
    *          description="Film id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="successful operation"
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found")
    * )
    */
    public function show($id)
    {
        $response = critics::where('film_id', '=', $id)->get();

        if (sizeof($response) == 0) {
            return response(["message"=>"No Critics with requested ID"], 404)->header('Content-Type', 'application/json');
        }
        return $response;
    }
}