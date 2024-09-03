<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rating',
        'book_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'book_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Get the book associated with the rating.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book() 
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user who gave the rating.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
