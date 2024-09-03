<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowStoreRequest;
use Illuminate\Http\Request;
use App\Models\Borrow_record;
use App\Services\BorrowService;
use Illuminate\Support\Facades\Log;
use App\Http\Trait\ApiResponceTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BorrowRecordResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class BorrowRecordController extends Controller
{
    protected $borrowService;
    use ApiResponceTrait;
    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService =  $borrowService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $borrowRecords = $this->borrowService->getAllBorrowsRecords($request);
            return $this->successResponse($borrowRecords, 'bring all borrowRecords successfully.', 200);
        } catch (\Exception $e) {
            return $this->handleException($e, ' error with bring all borrowRecords.',404);
        }
    }
 /**
     * Store a new borrowRecord
     * @param array <array> containing 'user_id', 'book_id', 'borrow_at', 'return_at'.
     * @return array containing the created borrow Record resource.
     * @throws \Exception
     * Throws an exception if the borrowRecord creation fails*/
    public function store(BorrowStoreRequest $data): array
    {
        // Create a new book
        $borrowRecord = Borrow_record::create([
            'user_id' => $data['user_id'],
            'book_id' => $data['book_id'],
            'borrowed_at' => $data['borrowed_at'],
            'returned_at' => $data['returned_at'],
         
        ]);

        // Check if the borrowRecord was created successfully
        if (!$borrowRecord) {
            throw new \Exception('Failed to create the borrowRecord.');
        }

        // Return the created borrowRecord as a resource
        return BorrowRecordResource::make($borrowRecord)->toArray(request());
    }

    /**
     * Retrieve a specific borrowRecord by its ID.
     * 
     * @param int $id
     * The ID of the borrowRecord to retrieve.
     * 
     * @return array
     * An array containing the borrowRecord resource.
     * 
     * @throws \Exception
     * Throws an exception if the borrowRecord is not found.
     */
    public function show(int $id): array
    {
        // Find the borrowRecord by ID
        $borrowRecord = Borrow_record::find($id);

        // If no borrowRecord is found, throw an exception
        if (!$borrowRecord) {
            throw new \Exception('borrowRecord not found.');
        }

        // Return the found borrowRecord as a resource
        return BorrowRecordResource::make($borrowRecord)->toArray(request());
    }

    /**
     * Update an existing borrowRecord.
     * 
     * @param Borrow_record borrowRecord $borrowRecord
     * The borrowRecord model instance to update.
     * @param array $data
     * An associative array containing the fields to update ( borrow_at, return_at).
     * 
     * @return array
     * An array containing the updated borrowRecord resource.
     */
    public function updateBorrowRecord(Borrow_record $borrowRecord, array $data): array
    {
        // Update only the fields that are provided in the data array
        $borrowRecord->update(array_filter([
            'borrowed_at' => $data['borrowed_at'] ?? $borrowRecord->borrowed_at,
            
            'returned_at' => $data['returned_at'] ?? $borrowRecord->returned_at,
        ]));

        // Return the updated borrowRecord as a resource
        return BorrowRecordResource::make($borrowRecord)->toArray(request());
    }

    /**
     * Delete a borrowRecord by its ID.
     * 
     * @param int $id
     * The ID of the borrowRecord to delete.
     * 
     * @return void
     * 
     * @throws \Exception
     * Throws an exception if the borrowRecord is not found.
     */
    public function deleteborrowRecord(int $id): void
    {
        // Find the borrowRecord by ID
        $borrowRecord = Borrow_record::find($id);

        // If no borrowRecord is found, throw an exception
        if (!$borrowRecord) {
            throw new \Exception('borrowRecord not found.');
        }

        // Delete the borrowRecord
        $borrowRecord->delete();
    }
    protected function handleException(\Exception $e, string $message): JsonResponse
    {
        // Log the error with additional context if needed
        Log::error($message, ['exception' => $e->getMessage(), 'request' => request()->all()]);

        return $this->errorResponse($message, [$e->getMessage()], 500);
    }

}
