<?php


namespace App\Controller;


use App\Entity\MediaComment;
use App\Entity\MediaReview;
use App\Repository\MediaReviewRepository;
use App\Repository\Scrapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{

    private function handleReviewForm(Request $request, string $mediaType, int $mediaId) {
        $media = new MediaReview();
        $reviewForm = $this->createFormBuilder($media)
                ->add('title')
                ->add('text', TextareaType::class)
                ->add('rate', HiddenType::class)
                ->getForm();
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $media->setCreatedAt(new \DateTime());
            $media->setAuthor($this->getUser());
            $media->setMediaType($mediaType);
            $media->setMediaId($mediaId);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($media);
            $entityManager->flush();
            return null;
        }
        return $reviewForm;
    }

    /**
     * @Route("/movie/{movieId}", name="movie-details")
     * @param Request $request
     * @param int $movieId
     * @return Response
     */
    public function movieDetails(Request $request, int $movieId)
    {
        $reviewForm = $this->handleReviewForm($request, 'movie', $movieId);
        if ($reviewForm == null) {
            return $this->redirect($request->getUri());
        }

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
                'comments' => $commentRepository->findBy(['mediaType' => 'movie', 'mediaId' => $movieId], ['created_at' => 'DESC']),
                'reviews' => $reviewRepository->findBy(['mediaType' => 'movie', 'mediaId' => $movieId], ['created_at' => 'DESC']),
                'reviewForm' => $reviewForm->createView()
        ]);
    }

    /**
     * @Route("/tv/{tvShowId}", name="tv-show-details")
     * @param Request $request
     * @param int $tvShowId
     * @return Response
     */
    public function tvShowDetails(Request $request, int $tvShowId)
    {
        $reviewForm = $this->handleReviewForm($request, 'tv', $tvShowId);
        if ($reviewForm == null) {
            return $this->redirect($request->getUri());
        }

        $locale = $request->getLocale();
        $showGenres = Scrapper::tvShowGenres($locale);
        $details = Scrapper::tvShowDetails($tvShowId, $locale);
        $recommendations = Scrapper::tvShowRecommendations($tvShowId, $locale);
        if($recommendations->total_results == 0) {
            $recommendations = Scrapper::tvShowSimilar($tvShowId, $locale);
        }
        $images = Scrapper::tvShowImages($tvShowId, $locale);
        $videos = Scrapper::tvShowVideos($tvShowId, $locale);
        $commentRepository = $this->getDoctrine()->getRepository(MediaComment::class);
        $reviewRepository = $this->getDoctrine()->getRepository(MediaReview::class);

        return $this->render('media/tvshow-details.html.twig', [
                'show' => $details,
                'recommendations' => $recommendations->results,
                'showGenres' => $showGenres,
                'images' => $images,
                'videos' => $videos->results,
                'comments' => $commentRepository->findBy(['mediaType' => 'tv', 'mediaId' => $tvShowId], ['created_at' => 'DESC']),
                'reviews' => $reviewRepository->findBy(['mediaType' => 'tv', 'mediaId' => $tvShowId], ['created_at' => 'DESC']),
                'reviewForm' => $reviewForm->createView()
        ]);
    }
}