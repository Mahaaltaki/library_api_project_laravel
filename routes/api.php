<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BorrowRecordController;



Route::group([
    'middleware' => 'api'
], function ($router) {
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
}); 
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
    
Route::apiResource('/users', UserController::class)->middleware('admin');

Route::apiResource('/books', BookController::class)->middleware('admin');
Route::apiResource('/borrowRecords', BorrowRecordController::class)->middleware('admin');
Route::apiResource('/books', BookController::class,['index']);
Route::apiResource('/borrowRecords', BorrowRecordController::class,['store']);