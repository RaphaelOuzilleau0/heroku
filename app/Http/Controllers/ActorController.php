<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\actors;
use App\Http\Resources\ActorResource;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = ActorResource::collection(actors::paginate(20));
        if (sizeof($response) == 0) return response('Succès mais vide!', 204); 
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
    * @OA\Get(
    *      path="/api/films/{id}/actors",
    *      operationId="getFilmActorsById",
    *      tags={"Films"},
    *      summary="Get actors of a specific film",
    *      description="Returns film's acotrs",
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
        $response = actors::where('actor_films.film_id', '=', $id)
        ->join('actor_films', 'actors.id', '=', 'actor_films.actor_id')
        ->select('actors.*')
        ->get();

        if (sizeof($response) == 0) return response('Succès mais vide!', 204); 
        return $response;
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
