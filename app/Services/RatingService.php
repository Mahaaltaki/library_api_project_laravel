<?php

namespace App\Services;

use App\Models\Rating;
use App\Http\Resources\RatingResource;

/**
 * Class RatingService
 * 
 * This service handles operations related to ratings, including storing, updating, and deleting ratings.
 */
class RatingService
{
    /**
     * Stored new rating.
     * 
     * @param array $data
     * array containing 'book_id', 'user_id', 'rating'.
     * 
     * @return array
     * array containing the created rating resource.
     * 
     * @throws \Exception
     * Throws an exception if  rating creation faild.
     */
    public function storeRating(array $data): array
    {
        // Create a new rating 
        $rating = Rating::create([
            'book_id' => $data['book_id'],
            'user_id' => $data['user_id'],
            'rating' => $data['rating'],
        ]);

        // if the rating did not created successfully
        if (!$rating) {
            throw new \Exception('Failed to create the Rating.');
        }

        // Return the rating as a resource
        return RatingResource::make($rating)->toArray(request());
    }

    /**
     * Update rating.
     * @param array $data
     * array containing 'rating' to be updated.
     * @param int $bookId
     * The ID of the book.
     * @param int $userId
     * The ID of the user who created the rating.
     * @return array
     * An array containing the updated rating resource.
     * @throws \Exception
     * Throws an exception if the rating update faild.
     */
    public function updateRating(array $data, int $bookId, int $userId): array
    {
        // search and fetch the rating based on book_id and user_id
        $rating = Rating::where('book_id', $bookId)->where('user_id', $userId)->first();

        // If the rating is not found, return error response
        if (!$rating) {
            return response()->json(['message' => 'Rating not found.'], 404);
        }

        // Update the fields 
        if (isset($data['rating'])) {
            $rating->rating = $data['rating'];
        }

        // Save the rating in the database
        $rating->save();

        // Return the updated rating as a resource
        return RatingResource::make($rating)->toArray(request());
    }

    /* Delete rating.
     * @param int $bookId
     * The ID of the book with the rating which linked with it.
     * @param int $userId
     * The ID of the user who created the rating.*/
    public function deleteRating(int $bookId, int $userId)
    {
        // select the book with rating by it,the user who make this rating
        $rating = Rating::where('book_id', $bookId)->where('user_id', $userId)->first();

        // If rating is not found, retur this response
        if (!$rating) {
            return response()->json(['message' => 'Rating is not found.'], 404);
        }

        // Delete the rating and return a success response
        $rating->delete();
        return response()->json(['message' => 'Rating has been deleted successfully'], 200); // OK
    }
}
