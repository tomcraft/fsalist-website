<?php


namespace App\Repository;


use GuzzleHttp\Client;

class Scrapper
{
    private static $client;

    public static function getClient() {
        if (self::$client == null) {
            self::$client = new Client([
                'base_uri'  => 'https://api.themoviedb.org/3/',
                'timeout'   => 2.0,
                'http_errors' => false,
                'headers'   => [
                    'Authorization' => 'Bearer ' . $_ENV['TMBD_API_KEY'],
                    'Content-Type' => 'application/json;charset=utf-8'
                ]
            ]);
        }
        return self::$client;
    }

    public static function get(string $url, array $options = []) {
        return self::getClient()->get($url, $options);
    }

    public static function imageUrl(string $relativeUrl, string $size = 'original') {
        return "https://image.tmdb.org/t/p/$size/$relativeUrl";
    }

    public static function searchAny(string $query, int $page = 1, string $lang = 'fr-FR') {
        return self::get("search/multi?query=$query&language=$lang&page=$page");
    }

    public static function searchMovie(string $query, int $page = 1, string $lang = 'fr-FR') {
        return self::get("search/movie?query=$query&language=$lang&page=$page");
    }

    public static function searchTvShow(string $query, int $page = 1, string $lang = 'fr-FR') {
        return self::get("search/tv?query=$query&language=$lang&page=$page");
    }

    public static function movieDetails(int $movieId, string $lang = 'fr-FR') {
        return self::get("movie/$movieId?language=$lang");
    }

    public static function movieImages(int $movieId, string $lang = 'fr-FR') {
        return self::get("movie/$movieId/images?language=$lang&include_image_language=en,null");
    }

    public static function movieVideos(int $movieId, string $lang = 'fr-FR') {
        return self::get("movie/$movieId/videos?language=$lang");
    }

    public static function movieSimilar(int $movieId, int $page = 1, string $lang = 'fr-FR') {
        return self::get("movie/$movieId/similar?language=$lang&page=$page");
    }

    public static function latestMovies(int $page = 1, string $lang = 'fr-FR') {
        return self::get("movie/latest?language=$lang&page=$page");
    }

    public static function nowPlayingMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = explode('-', $lang)[1];
        return self::get("movie/now_playing?language=$lang&region=$region&page=$page");
    }

    public static function popularMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = explode('-', $lang)[1];
        return self::get("movie/popular?language=$lang&region=$region&page=$page");
    }

    public static function topRatedMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = explode('-', $lang)[1];
        return self::get("movie/top_rated?language=$lang&region=$region&page=$page");
    }

    public static function upcomingMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = explode('-', $lang)[1];
        return self::get("movie/upcoming?language=$lang&region=$region&page=$page");
    }


}