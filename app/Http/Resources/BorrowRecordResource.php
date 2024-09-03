<?php

namespace App\Http\Resources;

use Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book_id'=> $this->book_id,
        'user_id' => $this->user_id,
            'borrowed_at' => $this->user_id,
            'due_date' => $this->due_date,
            'returned_at' => $this->returned_at,
        ];
    }
}
