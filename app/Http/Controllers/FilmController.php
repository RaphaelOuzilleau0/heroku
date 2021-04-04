<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use Illuminate\Support\Facades\DB;

class FilmController extends Controller
{     
    /**
    * @OA\Get(
    *      path="/api/films",
    *      operationId="getProjectsList",
    *      tags={"Films"},
    *      summary="Get list of films",
    *      description="Returns list of films",
    *      @OA\Response(
    *          response=200,
    *          description="successful operation"
    *       ),
    *       @OA\Response(response=400, description="Bad request")
    *     )
    *
    * Returns list of films
    */
    public function index()
    {
        $response = FilmResource::collection(Film::where(function($query) {
            if (isset($_GET["rating"]) && $_GET["rating"] != '') $query->where('rating', '=', $_GET["rating"]);
            if (isset($_GET["minlength"]) && $_GET["minlength"] != '') $query->where('length', '>=', $_GET["minlength"]);
            if (isset($_GET["maxlength"]) && $_GET["maxlength"] != '') $query->where('length', '<=', $_GET["maxlength"]);
            if (isset($_GET["keyword"])) $query->where((function($sQuery) {
                $sQuery ->where('title', 'LIKE', '%' . strval($_GET["keyword"]) . '%')       
                ->orWhere('description', 'LIKE', '%' . strval($_GET["keyword"]) . '%');
            }));
        })->orderBy('id')->paginate(20));

        if (sizeof($response) == 0) return response('Succès mais vide!', 204); 
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();
        try{
            $film = new Film;
            $film->title = $data['title'];
            $film->release_year = $data['release_year'];
            $film->length = $data['length'];
            $film->description = $data['description'];
            $film->rating = $data['rating'];
            $film->language_id = $data['language_id'];
            $film->special_features = $data['special_features'];
            $film->image = $data['image'];
            $film->created_at = $data['created_at'];
            $film->timestamps = false;
            
            $film->save();
            //show($user->id);
        }
        catch(Exception $e){
            //return redirect('insert')->with('failed',"operation failed");
        }
    }

    /**
    * @OA\Get(
    *      path="/api/films/{id}",
    *      operationId="getFilmById",
    *      tags={"Films"},
    *      summary="Get specific film",
    *      description="Returns film data",
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
        return Film::findOrFail($id);
    }

    public function showWithCritics($id)
    {
        $critics = DB::table('films')
            ->join('critics', 'films.id', '=', 'critics.film_id')
            ->select('critics.*')
            ->where('films.id', '=', $id)
            ->get();

        return $critics;
    }    

    public function showWithActors($id)
    {   
        $actors = DB::table('films')
            ->join('actor_films', 'films.id', '=', 'actor_films.film_id')
            ->join('actors', 'actor_films.actor_id', '=', 'actors.id')
            ->select('actors.*')
            ->where('films.id', '=', $id)
            ->get();
    
        return $actors;
    } 
}
