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

    public static function cache(string $url, array $options = []) {
        return self::get($url, $options)->getBody();
    }

    public static function imageUrl(string $relativeUrl, string $size = 'original') {
        return "https://image.tmdb.org/t/p/$size/$relativeUrl";
    }

    private static function regionFromLang(string $lang) {
        if(strpos($lang, '-') !== false) {
            return explode('-', $lang)[1];
        }
        return strtoupper($lang);
    }

    public static function movieGenres(string $lang = 'fr-FR') {
        $data = array();
        $genres = json_decode(self::cache("genre/movie/list?language=$lang"))->genres;
        foreach ($genres as $genre) {
            $data[$genre->id] = $genre->name;
        }
        return $data;
    }

    public static function tvShowGenres(string $lang = 'fr-FR') {
        $data = array();
        $genres = json_decode(self::cache("genre/tv/list?language=$lang"))->genres;
        foreach ($genres as $genre) {
            $data[$genre->id] = $genre->name;
        }
        return $data;
    }

    public static function searchAny(string $query, int $page = 1, string $lang = 'fr-FR') {
        return self::cache("search/multi?query=$query&language=$lang&page=$page");
    }

    public static function searchMovie(string $query, int $page = 1, string $lang = 'fr-FR') {
        return self::cache("search/movie?query=$query&language=$lang&page=$page");
    }

    public static function searchTvShow(string $query, int $page = 1, string $lang = 'fr-FR') {
        return self::cache("search/tv?query=$query&language=$lang&page=$page");
    }

    public static function movieDetails(int $movieId, string $lang = 'fr-FR') {
        return self::cache("movie/$movieId?language=$lang");
    }

    public static function movieImages(int $movieId, string $lang = 'fr-FR') {
        return self::cache("movie/$movieId/images?language=$lang&include_image_language=en,null");
    }

    public static function movieVideos(int $movieId, string $lang = 'fr-FR') {
        return self::cache("movie/$movieId/videos?language=$lang");
    }

    public static function movieSimilar(int $movieId, int $page = 1, string $lang = 'fr-FR') {
        return self::cache("movie/$movieId/similar?language=$lang&page=$page");
    }

    public static function nowPlayingMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/now_playing?language=$lang&region=$region&page=$page");
    }

    public static function popularMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/popular?language=$lang&region=$region&page=$page");
    }

    public static function topRatedMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/top_rated?language=$lang&region=$region&page=$page");
    }

    public static function upcomingMovies(int $page = 1, string $lang = 'fr-FR', string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/upcoming?language=$lang&region=$region&page=$page");
    }

}