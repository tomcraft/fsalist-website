<?php


namespace App\Controller;


use App\Repository\Scrapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DebugMailerController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/discover", name="discover")
     * @param Request $request
     * @return Response
     */
    public function discover(Request $request)
    {
        $locale = $request->getLocale();

        $page = $request->get('page', 1);

        $optionsMovies = [];
        $optionsShows = [];

        $genre = $request->get('genre');
        $yearMin = $request->get('year-min');
        $yearMax = $request->get('year-max');
        $rateMin = $request->get('rate-min');
        $rateMax = $request->get('rate-max');

        if ($genre) {
            $optionsMovies['with_genres'] = $genre;
            $optionsShows['with_genres'] = $genre;
        }
        if ($yearMin) {
            $optionsMovies['release_date.gte'] = "$yearMin-01-01";
            $optionsShows['air_date.gte'] = "$yearMin-01-01";
        }
        if ($yearMax) {
            $optionsMovies['release_date.lte'] = "$yearMin-12-31";
            $optionsShows['air_date.lte'] = "$yearMin-12-31";
        }
        if ($rateMin) {
            $optionsMovies['vote_average.gte'] = $rateMin;
            $optionsShows['vote_average.gte'] = $rateMin;
        }
        if ($rateMax) {
            $optionsMovies['vote_average.lte'] = $rateMax;
            $optionsShows['vote_average.lte'] = $rateMax;
        }

        $movies = Scrapper::discoverMovie($optionsMovies, $locale, $page);
        foreach ($movies->results as $movie) {
            $movie->media_type = 'movie';
        }
        $shows = Scrapper::discoverTvShow($optionsShows, $locale, $page);
        foreach ($shows->results as $show) {
            $show->media_type = 'tv';
        }
        $medias = array_merge($movies->results, $shows->results);

        $genres = Scrapper::mediaGenres($locale);

        return $this->render('search/discover.html.twig', [
                'titleKey' => 'discover.title',
                'filters'=> true,
                'medias' => $medias,
                'genres' => $genres,
                'page' => $page,
                'max_page' => max($movies->total_pages, $shows->total_pages),
                'last_form' => $request->query->all(),
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $locale = $request->getLocale();
        $query = $request->get('q');
        $page = $request->get('page', 1);
        $result = Scrapper::searchAny($query, $page, $locale);

        $mediaArray = $result->results;
        $mediaArray = array_filter($mediaArray, function ($movie) {
            return $movie->media_type == 'tv' || $movie->media_type == 'movie';
        });

        if ($page == 1 && count($mediaArray) == 1) {
            $media = $mediaArray[0];
            $mediaType = $media->media_type;

            if ($mediaType == 'movie') {
                return $this->redirectToRoute('movie-details', ['movieId' => $media->id]);
            }

            return $this->redirectToRoute('tv-show-details', ['tvShowId' => $media->id]);
        }

        $genres = Scrapper::mediaGenres($locale);

        return $this->render('search/discover.html.twig', [
                'titleKey' => 'search.title',
                'filters'=> false,
                'medias' => $mediaArray,
                'genres' => $genres,
                'page' => $page,
                'max_page' => $result->total_pages
        ]);
    }

}