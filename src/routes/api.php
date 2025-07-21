<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IdeaController;
use App\Http\Controllers\Api\VoteController;

Route::apiResource('ideas', IdeaController::class);
Route::apiResource('votes', VoteController::class);