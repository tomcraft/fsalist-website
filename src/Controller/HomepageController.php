<?php


namespace App\Controller;


use App\Repository\Scrapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $locale = $request->getLocale();
        $nowPlayingMovies = Scrapper::nowPlayingMovies($locale);
        $upcomingMovies = Scrapper::upcomingMovies($locale);
        $onAirTvShows = Scrapper::onTheAirTvShow($locale);
        $trendingAnimes = Scrapper::discoverTvShow(['sort_by' => 'popularity.desc', 'with_genres' => '16'], $locale);
        $genres = Scrapper::mediaGenres($locale);

        return $this->render('homepage.html.twig', [
                'nowPlayingMovies' => $nowPlayingMovies->results,
                'upcomingMovies' => $upcomingMovies->results,
                'onAirTvShows' => $onAirTvShows->results,
                'trendingAnimes' => $trendingAnimes->results,
                'genres' => $genres
        ]);
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('privacy.html.twig');
    }

    /**
     * @Route("/cookies", name="cookies")
     */
    public function cookies()
    {
        return $this->render('cookies.html.twig');
    }

}