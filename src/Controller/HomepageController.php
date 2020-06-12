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
        $movieGenres = Scrapper::movieGenres($locale);
        $showGenres = Scrapper::tvShowGenres($locale);

        return $this->render('homepage.html.twig', [
                'nowPlayingMovies' => $nowPlayingMovies->results,
                'upcomingMovies' => $upcomingMovies->results,
                'onAirTvShows' => $onAirTvShows->results,
                'movieGenres' => $movieGenres,
                'showGenres' => $showGenres,
        ]);
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('privacy.html.twig');
    }

}