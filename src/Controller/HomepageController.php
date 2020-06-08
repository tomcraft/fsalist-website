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
        $popularMovies = Scrapper::nowPlayingMovies($locale);
        $upcomingMovies = Scrapper::upcomingMovies($locale);
        $movieGenres = Scrapper::movieGenres($locale);

        return $this->render('homepage.html.twig', [
            'popularMovies' => $popularMovies->results,
            'upcomingMovies' => $upcomingMovies->results,
            'movieGenres' => $movieGenres,
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