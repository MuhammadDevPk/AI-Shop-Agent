<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EscrowChatController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/chat', [ChatController::class, 'index']);
// Route::get('/chat/stream', [ChatController::class, 'stream'])->name('chat.stream');

Route::get('/', [ChatController::class, 'index']);
Route::get('/chat/stream', [EscrowChatController::class, 'handleMessage'])->name('chat.stream');