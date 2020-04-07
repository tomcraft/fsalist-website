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
        $popularMovies = Scrapper::nowPlayingMovies(1, $locale);
        $upcomingMovies = Scrapper::upcomingMovies(1, $locale);
        $movieGenres = Scrapper::movieGenres($locale);

        return $this->render('homepage.html.twig', [
            'popularMovies' => json_decode($popularMovies)->results,
            'upcomingMovies' => json_decode($upcomingMovies)->results,
            'movieGenres' => $movieGenres,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('contact.html.twig');
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('privacy.html.twig');
    }

}