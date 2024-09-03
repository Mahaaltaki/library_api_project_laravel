<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Borrow_record;
use App\Http\Resources\BorrowRecordResource;
class BorrowService
{
    /*
     * @param Request $request
     * @return array containing detials about borrow record resources.
     */
    public function getAllBorrowsRecords(Request $request): array
    {
        // query for the borrow record with users
        $query = Borrow_record::with('user');

        // Paginate the results
        $borrowRecords = $query->paginate(10);

        // Return the paginated borrowRecords wrapped in a BorrowResource collection
        return BorrowRecordResource::collection($borrowRecords)->toArray(request());
    }

    /**
     * Store a new borrowRecord
     * @param array <array> containing 'user_id', 'book_id', 'return_at', 'borrow_at'.
     * @return array containing the created borrowRecord resource.
     * @throws \Exception
     * Throws an exception if the borrowRecord creation fails*/
    public function storeborrowRecord(array $data): array
    {
        // Create a new book
        $borrowRecord = Borrow_record::create([
            'user_id' => $data['user_id'],
            'book_id' => $data['book_id'],
            'borrowed_at' => $data['borrowed_at'],
            'returned_at' => $data['returned_at'],
         
        ]);

        // Check if the borrow record was created successfully
        if (!$borrowRecord) {
            throw new \Exception('Failed to create the borrowRecord.');
        }

        // Return the created borrow record as a resource
        return BorrowRecordResource::make($borrowRecord)->toArray(request());
    }

    /**
     * Retrieve a specific borrow record by its ID.
     * 
     * @param int $id
     * The ID of the borrow record to retrieve.
     * 
     * @return array
     * An array containing the borrow record resource.
     * 
     * @throws \Exception
     * Throws an exception if the borrow record is not found.
     */
    public function showBorrowRecord(int $id): array
    {
        // Find the borrow record by ID
        $borrowRecord = Borrow_record::find($id);

        // If no borrow record is found, throw an exception
        if (!$borrowRecord) {
            throw new \Exception('borrowRecord not found.');
        }

        // Return the found borrow record as a resource
        return BorrowRecordResource::make($borrowRecord)->toArray(request());
    }

    /**
     * Update an existing borrow record.
     * 
     * @param $borrowRecord $borrow record
     * The borrow record model instance to update.
     * @param array $data
     * An associative array containing the fields to update (title, athor, description, published_at).
     * 
     * @return array
     * An array containing the updated borrow record resource.
     */
    public function updateBorrowRecord(Borrow_record $borrowRecord, array $data): array
    {
        // Update only the fields that are provided in the data array
        $borrowRecord->update(array_filter([
            'book_id' => $data['book_id'] ?? $borrowRecord->book_id,
            'user_id' => $data['user_id'] ?? $borrowRecord->user_id,
            'borrowed_at' => $data['borrowed_at'] ?? $borrowRecord->borrpw_at,
            'returned_at' => $data['returned_at'] ?? $borrowRecord->return_at,
        ]));

        // Return the updated borrow record as a resource
        return BorrowRecordResource::make($borrowRecord)->toArray(request());
    }

    /**
     * Delete a borrow record by its ID.
     * 
     * @param int $id
     * The ID of the borrow record to delete.
     * 
     * @return void
     * 
     * @throws \Exception
     * Throws an exception if the borrow record is not found.
     */
    public function deleteBorrowRecord(int $id): void
    {
        // Find the borrow record by ID
        $borrowRecord = Borrow_record::find($id);

        // If no borrow record is found, throw an exception
        if (!$borrowRecord) {
            throw new \Exception('borrow record not found.');
        }

        // Delete the borrow record
        $borrowRecord->delete();
    }
}
