<?php

namespace App\Http\Controllers\Api;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Trait\ApiResponserTrait;
use App\Http\Requests\RatingStoreRequest;
use App\Http\Requests\RatingUpdateRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;


class RatingController extends Controller
{
    protected $ratingService;
    use ApiResponserTrait;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $rating = $this->ratingService->storeRating($validatedData);
            return $this->successResponse($rating, 'Rating created successfully.', 201);
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while storing the rating.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(RatingUpdateRequest $request, $movieId): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $userId = auth()->id();
            $updatedRatingResource = $this->ratingService->updateRating($validatedData, $movieId, $userId);

            return $this->successResponse($updatedRatingResource, 'Rating updated successfully.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while updating the rating.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($movieId): JsonResponse
    {
        try {
            $userId = auth()->id();
            return $this->ratingService->deleteRating($movieId, $userId);
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while deleting the rating.');
        }
    }

    protected function handleException(\Exception $e, $message): JsonResponse
    {
        Log::error($e->getMessage());

        return $this->errorResponse($message, [$e->getMessage()], 500);
    }
}
