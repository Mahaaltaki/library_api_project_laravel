<?php

namespace App\Http\Controllers\Api;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Trait\ApiResponceTrait;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookController extends Controller
{
    protected $bookService;
    use ApiResponceTrait;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $books = $this->bookService->getAllBooks($request);
            return $this->successResponse($books, 'bring all books successfully.', 200);
        } catch (\Exception $e) {
            return $this->handleException($e, ' error with bring all books.',404);
        }
    }

    /**
     * Store a newl book.
     */
    public function store(BookStoreRequest $request)
    {
        try{
            $validatedRequest=$request->validated();
            $book=$this->bookService->storebook($validatedRequest);
            return $this->successResponse($book,'the book stored successfuly',201);
        }catch(\Exception $e){
            return $this->handleException($e, ' error with stored the book',);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
        
            $book=$this->bookService->showbook($id);
            return $this->successResponse($book,'the book has been showing successfuly',200);
        }catch(\Exception $e){
         return $this->handleException($e,'error with showing the book');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, Book $book)
    {try{
        if(!$book->exists){
            return $this->notFound('the book not found');
        }
        $validated=$request->validated();
        $updatedBook=$this->bookService->updatebook($book,$validated);
       return $this->successResponse($updatedBook,'the book updated successfuly',200);
    }catch(\Exception $e){
        return $this->handleException($e,'error with updating book');

    }
    }

    /**
     * Remove the one object from storage.
     */
    public function destroy(string $id):JsonResponse
    {
         try {
            $this->bookService->deletebook($id);
            return $this->successResponse([], 'the book deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->handleException($e, 'error with deleting the book');
        }
    }
    /**
     * Handle exceptions and show a response.
     */
    protected function handleException(\Exception $e, string $message): JsonResponse
    {
        // Log the error with additional context if needed
        Log::error($message, ['exception' => $e->getMessage(), 'request' => request()->all()]);

        return $this->errorResponse($message, [$e->getMessage()], 500);
    }
}
