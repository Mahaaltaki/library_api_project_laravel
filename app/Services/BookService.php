<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\RatingResource;

class BookService
{
    /*
     * @param Request $request
     * @return array containing detials about book resources.
     */
    public function getAllBooks(Request $request): array
    {
        // query for the Book with rating
        $query = Book::with('ratings');
        $ratings= RatingResource::collection($this->ratings ?: collect([])); // Ensure empty array if no ratings
            
        $avg=$ratings->averageRating();
        // Paginate the results
        $books = $query->paginate(10);

        // Return the paginated books wrapped in a BookResource collection
        return BookResource::collection($books)->toArray(request());
    }

    /**
     * Store a new book
     * @param array <array> containing 'title', 'author', 'description', 'published_at'.
     * @return array containing the created book resource.
     * @throws \Exception
     * Throws an exception if the book creation fails*/
    public function storebook(array $data): array
    {
        // Create a new book
        $book = Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'],
            'published_at' => $data['published_at'],
         
        ]);

        // Check if the book was created successfully
        if (!$book) {
            throw new \Exception('Failed to create the book.');
        }

        // Return the created book as a resource
        return BookResource::make($book)->toArray(request());
    }

    /**
     * Retrieve a specific book by its ID.
     * 
     * @param int $id
     * The ID of the book to retrieve.
     * 
     * @return array
     * An array containing the book resource.
     * 
     * @throws \Exception
     * Throws an exception if the book is not found.
     */
    public function showbook(int $id): array
    {
        // Find the book by ID
        $book = book::find($id);

        // If no book is found, throw an exception
        if (!$book) {
            throw new \Exception('book not found.');
        }

        // Return the found book as a resource
        return bookResource::make($book)->toArray(request());
    }

    /**
     * Update an existing book.
     * 
     * @param book $book
     * The book model instance to update.
     * @param array $data
     * An associative array containing the fields to update (title, athor, description, published_at).
     * 
     * @return array
     * An array containing the updated book resource.
     */
    public function updatebook(Book $book, array $data): array
    {
        // Update only the fields that are provided in the data array
        $book->update(array_filter([
            'title' => $data['title'] ?? $book->title,
            'director' => $data['director'] ?? $book->director,
            'genre' => $data['genre'] ?? $book->genre,
            'release_year' => $data['release_year'] ?? $book->release_year,
            'description' => $data['description'] ?? $book->description,
            

        ]));

        // Return the updated book as a resource
        return BookResource::make($book)->toArray(request());
    }

    /**
     * Delete a book by its ID.
     * 
     * @param int $id
     * The ID of the book to delete.
     * 
     * @return void
     * 
     * @throws \Exception
     * Throws an exception if the book is not found.
     */
    public function deletebook(int $id): void
    {
        // Find the book by ID
        $book = Book::find($id);

        // If no book is found, throw an exception
        if (!$book) {
            throw new \Exception('book not found.');
        }

        // Delete the book
        $book->delete();
    }
}
