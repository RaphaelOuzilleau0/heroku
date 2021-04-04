<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'release_year' => $this->release_year,
            'length' => $this->length,
            'description' => $this->description,
            'rating' => $this->rating,
            'language_id' => $this->language_id,
            'special_features' => $this->special_features,
            'image' => $this->image,
            'created_at' => $this->created_at
            ];
    }
}
