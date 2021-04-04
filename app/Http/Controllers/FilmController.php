<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateFilmRequest;

class FilmController extends Controller
{
    /**
    *@OA\Get(
    *   path="/api/films",
    *   tags={"Films"},
    *   summary="Get list of films",
    *   @OA\Response(
    *       response=200,
    *       description="OK"
    *   ),
    *   @OA\Response(response=400, description="Bad request")
    *)
    */
    public function index()
    {
        $response = FilmResource::collection(Film::where(function ($query) {
            if (isset($_GET["rating"]) && $_GET["rating"] != '') {
                $query->where('rating', '=', $_GET["rating"]);
            }
            if (isset($_GET["minlength"]) && $_GET["minlength"] != '') {
                $query->where('length', '>=', $_GET["minlength"]);
            }
            if (isset($_GET["maxlength"]) && $_GET["maxlength"] != '') {
                $query->where('length', '<=', $_GET["maxlength"]);
            }
            if (isset($_GET["keyword"])) {
                $query->where((function ($sQuery) {
                    $sQuery ->where('title', 'LIKE', '%' . strval($_GET["keyword"]) . '%')
                ->orWhere('description', 'LIKE', '%' . strval($_GET["keyword"]) . '%');
                }));
            }
        })->orderBy('id')->paginate(20));

        if (sizeof($response) == 0) {
            return response(["message"=>"Success but empty"], 204)->header('Content-Type', 'application/json');
        }
        return $response;
    }

    /**
    * @OA\Post(
    *   path="/api/films",
    *   tags={"Films"},
    *   summary="Add a new film",
    *   @OA\RequestBody(
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="title",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                  property="release_year",
    *                  type="string"
    *               ),
    *               @OA\Property(
    *                  property="length",
    *                  type="integer"
    *               ),
    *               @OA\Property(
    *                  property="description",
    *                  type="string"
    *                ),
    *               @OA\Property(
    *                   property="rating",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="language_id",
    *                   type="integer"
    *               ),
    *               @OA\Property(
    *                   property="special_features",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="image",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="created_at",
    *                   type="string"
    *               ),
    *               example={"title": "title", "release_year": "2000", "length": "100", "description": "description", "rating": "G", "language_id": 0, "special_features": "features", "image": "image", "created_at": "2000-01-01"}
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
    public function store(CreateFilmRequest $request)
    {
        return Film::create($request->validated());
    }

    /**
    *@OA\Get(
    *   path="/api/films/{id}",
    *   operationId="getFilmById",
    *   tags={"Films"},
    *   summary="Get specific film",
    *   description="Returns film data",
    *   @OA\Parameter(
    *       name="id",
    *       description="Film id",
    *       required=true,
    *       in="path",
    *       @OA\Schema(
    *           type="integer"
    *        )
    *   ),
    *   @OA\Response(
    *       response=200,
    *        description="successful operation"
    *   ),
    *   @OA\Response(response=400, description="Bad request"),
    *   @OA\Response(response=404, description="Resource Not Found")
    *)
    */
    public function show($id)
    {
        return Film::findOrFail($id);
    }

    /**
    *@OA\Put(
    *   path="/api/films/{id}",
    *   tags={"Films"},
    *   summary="Update a specific film",
    *   @OA\Parameter(
    *       name="id",
    *       required=true,
    *       in="path",
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\RequestBody(
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="title",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                  property="release_year",
    *                  type="string"
    *               ),
    *               @OA\Property(
    *                  property="length",
    *                  type="integer"
    *               ),
    *               @OA\Property(
    *                  property="description",
    *                  type="string"
    *                ),
    *               @OA\Property(
    *                   property="rating",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="language_id",
    *                   type="integer"
    *               ),
    *               @OA\Property(
    *                   property="special_features",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="image",
    *                   type="string"
    *               ),
    *               @OA\Property(
    *                   property="created_at",
    *                   type="string"
    *               ),
    *               example={"title": "title", "release_year": "2000", "length": "100", "description": "description", "rating": "G", "language_id": 0, "special_features": "features", "image": "image", "created_at": "2000-01-01"}
    *           )
    *       )
    *   ),
    *   security={{ "bearerAuth":{} }},
    *   @OA\Response(
    *       response=200,
    *       description="successful operation",
    *   ),
    *   @OA\Response(response=400, description="Bad request"),
    *   @OA\Response(response=404, description="Resource Not Found"),
    *)
    */
    public function update(Request $request, $id)
    {
        $data = $request->input();

        $film = Film::find($id);
        if (isset($data['title'])) {
            $film->title = $data['title'];
        }
        if (isset($data['release_year'])) {
            $film->release_year = $data['release_year'];
        }
        if (isset($data['length'])) {
            $film->length = $data['length'];
        }
        if (isset($data['description'])) {
            $film->description = $data['description'];
        }
        if (isset($data['rating'])) {
            $film->rating = $data['rating'];
        }
        if (isset($data['language_id'])) {
            $film->language_id = $data['language_id'];
        }
        if (isset($data['special_features'])) {
            $film->special_features = $data['special_features'];
        }
        if (isset($data['image'])) {
            $film->image = $data['image'];
        }
        $film->save();
        return $this->show($id);
    }

    /**
    *@OA\Delete(
    *   path="/api/films/{id}",
    *   tags={"Films"},
    *   summary="Update a specific film",
    *   @OA\Parameter(
    *       name="id",
    *       required=true,
    *       in="path",
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   security={{ "bearerAuth":{} }},
    *   @OA\Response(
    *       response=200,
    *       description="Successfully Deleted Film",
    *   ),
    *   @OA\Response(response=400, description="Bad request"),
    *   @OA\Response(response=404, description="Resource Not Found"),
    *)
    */
    public function destroy($id)
    {
        DB::table('films')->where('films.id', '=', $id)->delete();
        return response(["message"=>"Successfully Deleted Film"], 200)->header('Content-Type', 'application/json');
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