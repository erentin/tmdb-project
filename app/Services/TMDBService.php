<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Repositories\FilmRepository;
use App\Repositories\SeriesRepository;
use App\Repositories\SeasonRepository;
use App\Repositories\EpisodeRepository;

use GuzzleHttp\Client;

class TMDBService
{
    protected $client;
    protected $apiKey;
    protected $publicApiKey;
    protected $filmRepository;
    protected $seriesRepository;
    protected $seasonRepository;
    protected $episodeRepository;

    public function __construct(
        FilmRepository $filmRepository,
        SeriesRepository $seriesRepository,
        SeasonRepository $seasonRepository,
        EpisodeRepository $episodeRepository
    ) {

        $this->client = new Client([
            'base_uri' => 'https://api.themoviedb.org/3/',
        ]);
        $this->apiKey = config('services.tmdb.auth_api');
        $this->publicApiKey = config('services.tmdb.api_public_key');
        $this->filmRepository = $filmRepository;
        $this->seriesRepository = $seriesRepository;
        $this->seasonRepository = $seasonRepository;
        $this->episodeRepository = $episodeRepository;
    }

    public function getPopularMovies()
    {
        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'accept' => 'application/json',
        ])->get('https://api.themoviedb.org/3/movie/popular');

        $data = $response->json();

        if (isset($data['results'])) {
            return $data['results'];
        } else {
            // Handle the case where 'results' key is not present
            \Log::error('TMDB API returned unexpected response for popular movies: '.json_encode($data));
            return [];
        }
    }
    
    public function getPopularSeries()
    {

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'accept' => 'application/json',
        ])->get('https://api.themoviedb.org/3/tv/popular"');

        $data = $response->json();
     
        if (isset($data['results'])) {
            return $data['results'];
        } else {
            // Handle the case where 'results' key is not present
            \Log::error('TMDB API returned unexpected response for popular series: '.json_encode($data));
            return [];
        }
    }
    

    public function importMovies()
    {
        $movies = $this->getPopularMovies();

        foreach ($movies as $movie) {
            $this->filmRepository->create([
                'title' => $movie['title'],
                'overview' => $movie['overview'],
                'release_date' => $movie['release_date'],
                'poster_path' => $movie['poster_path'],
            ]);
        }
    }

    public function importSeries()
    {
        $series = $this->getPopularSeries();

        foreach ($series as $serie) {
            $createdSeries = $this->seriesRepository->create([
                'name' => $serie['name'],
                'overview' => $serie['overview'],
                'first_air_date' => $serie['first_air_date'],
                'poster_path' => $serie['poster_path'],
            ]);

            // Sezonları ve Bölümleri Çekmek
            $this->importSeasons($createdSeries->id, $serie['id']);
        }
    }

    public function importSeasons($seriesId, $tmdbSeriesId)
    {
        $response = Http::get("https://api.themoviedb.org/3/tv/{$tmdbSeriesId}", [
            'Authorization' => $this->apiKey,
            'accept' => 'application/json',
        ]);

        $seasons = $response->json()['seasons'];

        foreach ($seasons as $season) {
            $createdSeason = $this->seasonRepository->create([
                'series_id' => $seriesId,
                'season_number' => $season['season_number'],
                'overview' => $season['overview'],
                'air_date' => $season['air_date'],
                'poster_path' => $season['poster_path'],
            ]);

            // Bölümleri Çekmek
            $this->importEpisodes($createdSeason->id, $tmdbSeriesId, $season['season_number']);
        }
    }

    public function importEpisodes($seasonId, $tmdbSeriesId, $seasonNumber)
    {
        $response = Http::get("https://api.themoviedb.org/3/tv/{$tmdbSeriesId}/season/{$seasonNumber}", [
            'Authorization' => $this->apiKey,
            'accept' => 'application/json',
        ]);

        $episodes = $response->json()['episodes'];

        foreach ($episodes as $episode) {
            $this->episodeRepository->create([
                'season_id' => $seasonId,
                'episode_number' => $episode['episode_number'],
                'name' => $episode['name'],
                'overview' => $episode['overview'],
                'air_date' => $episode['air_date'],
                'still_path' => $episode['still_path'],
            ]);
        }
    }


}
