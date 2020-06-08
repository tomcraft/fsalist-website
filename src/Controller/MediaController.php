<?php


namespace App\Controller;


use App\Entity\MediaComment;
use App\Entity\MediaReview;
use App\Repository\MediaReviewRepository;
use App\Repository\Scrapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/movie/{movieId}", name="movie-details")
     * @param Request $request
     * @param int $movieId
     * @return Response
     */
    public function movieDetails(Request $request, int $movieId)
    {
        $locale = $request->getLocale();
        $movieGenres = Scrapper::movieGenres($locale);
        $details = Scrapper::movieDetails($movieId, $locale);
        $credits = Scrapper::movieCredits($movieId);
        $recommendations = Scrapper::movieRecommendations($movieId, $locale);
        if($recommendations->total_results == 0) {
            $recommendations = Scrapper::movieSimilar($movieId, $locale);
        }
        $images = Scrapper::movieImages($movieId, $locale);
        $videos = Scrapper::movieVideos($movieId, $locale);
        $commentRepository = $this->getDoctrine()->getRepository(MediaComment::class);
        $reviewRepository = $this->getDoctrine()->getRepository(MediaReview::class);
        return $this->render('media/movie-details.html.twig', [
            'movie' => $details,
            'credits' => $credits,
            'recommendations' => $recommendations->results,
            'movieGenres' => $movieGenres,
            'images' => $images,
            'videos' => $videos->results,
            'comments' => $commentRepository->findBy(['mediaId' => $movieId], ['created_at' => 'DESC']),
            'reviews' => $reviewRepository->findBy(['mediaId' => $movieId], ['created_at' => 'DESC'])
        ]);
    }
}