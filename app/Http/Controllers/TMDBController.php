<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;

class TMDBController extends Controller
{
    protected $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function importMovies()
    {
        $this->tmdbService->importMovies();
        return response()->json(['message' => 'Movies imported successfully!']);
    }

    public function importSeries()
    {
        $this->tmdbService->importSeries();
        return response()->json(['message' => 'Series imported successfully!']);
    }
}
