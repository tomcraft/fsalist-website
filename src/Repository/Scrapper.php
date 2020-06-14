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
        return json_decode(self::get($url, $options)->getBody());
    }

    public static function imageUrl(string $relativeUrl, string $size = 'original') {
        return "https://image.tmdb.org/t/p/$size$relativeUrl";
    }

    private static function regionFromLang(string $lang) {
        if(strpos($lang, '-') !== false) {
            return explode('-', $lang)[1];
        }
        return strtoupper($lang);
    }

    public static function queryGenres(string $mediaType, string $lang = 'fr-FR') {
        $data = array();
        $genres = self::cache("genre/$mediaType/list?language=$lang")->genres;
        foreach ($genres as $genre) {
            $data[$genre->id] = $genre->name;
        }
        return $data;
    }

    public static function mediaGenres(string $lang = 'fr-FR') {
        $movies = self::queryGenres('movie', $lang);
        $shows = self::queryGenres('tv', $lang);
        return $movies + $shows;
    }

    public static function discoverMovie(array $options, string $lang = 'fr-FR', int $page = 1, string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        $options['language'] = $lang;
        $options['region'] = $region;
        $options['page'] = $page;
        return self::cache("discover/movie?".http_build_query($options));
    }

    public static function discoverTvShow(array $options, string $lang = 'fr-FR', int $page = 1, string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        $options['language'] = $lang;
        $options['region'] = $region;
        $options['page'] = $page;
        return self::cache("discover/tv?".http_build_query($options));
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

    public static function movieCredits(int $movieId) {
        return self::cache("movie/$movieId/credits");
    }

    public static function movieSimilar(int $movieId, string $lang = 'fr-FR', int $page = 1) {
        return self::cache("movie/$movieId/similar?language=$lang&page=$page");
    }

    public static function movieRecommendations(int $movieId, string $lang = 'fr-FR', int $page = 1) {
        return self::cache("movie/$movieId/recommendations?language=$lang&page=$page");
    }

    public static function nowPlayingMovies(string $lang = 'fr-FR', int $page = 1, string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/now_playing?language=$lang&region=$region&page=$page");
    }

    public static function popularMovies(string $lang = 'fr-FR', int $page = 1, string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/popular?language=$lang&region=$region&page=$page");
    }

    public static function topRatedMovies(string $lang = 'fr-FR', int $page = 1, string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/top_rated?language=$lang&region=$region&page=$page");
    }

    public static function upcomingMovies(string $lang = 'fr-FR', int $page = 1, string $region = null) {
        if($region == null) $region = self::regionFromLang($lang);
        return self::cache("movie/upcoming?language=$lang&region=$region&page=$page");
    }

    public static function tvShowDetails(int $tvShowId, string $lang = 'fr-FR') {
        return self::cache("tv/$tvShowId?language=$lang");
    }

    public static function tvShowImages(int $tvShowId, string $lang = 'fr-FR') {
        return self::cache("tv/$tvShowId/images?language=$lang&include_image_language=en,null");
    }

    public static function tvShowVideos(int $tvShowId, string $lang = 'fr-FR') {
        return self::cache("tv/$tvShowId/videos?language=$lang");
    }

    public static function tvShowCredits(int $tvShowId) {
        return self::cache("tv/$tvShowId/credits");
    }

    public static function onTheAirTvShow(string $lang = 'fr-FR', int $page = 1) {
        return self::cache("tv/on_the_air?language=$lang&page=$page");
    }

    public static function popularTvShow(string $lang = 'fr-FR', int $page = 1) {
        return self::cache("tv/popular?language=$lang&page=$page");
    }

    public static function topRatedTvShow(string $lang = 'fr-FR', int $page = 1) {
        return self::cache("tv/top_rated?language=$lang&page=$page");
    }

    public static function tvShowRecommendations(int $tvShowId, string $lang = 'fr-FR', int $page = 1) {
        return self::cache("tv/$tvShowId/recommendations?language=$lang&page=$page");
    }

    public static function tvShowSimilar(int $tvShowId, string $lang = 'fr-FR', int $page = 1) {
        return self::cache("tv/$tvShowId/similar?language=$lang&page=$page");
    }

}