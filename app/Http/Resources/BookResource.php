<?php

namespace App\Http\resources;

use Illuminate\Http\Request;
use App\Http\Resources\RatingResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
              'description' => $this->description,
            'published_at' => $this->published_at,
         
            
        ];
    }
}
