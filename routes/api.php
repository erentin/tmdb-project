<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TMDBController;

Route::get('import-movies', [TMDBController::class, 'importMovies']);
Route::get('import-series', [TMDBController::class, 'importSeries']);
