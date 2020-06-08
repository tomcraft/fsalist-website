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
    /**
     * @Route("/movie/{movieId}", name="movie-details")
     * @param Request $request
     * @param int $movieId
     * @return Response
     */
    public function movieDetails(Request $request, int $movieId)
    {
        $media = new MediaReview();
        $reviewForm = $this->createFormBuilder($media)
            ->add('title')
            ->add('text', TextareaType::class)
            ->add('rate', HiddenType::class)
            ->getForm();

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

        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $media->setCreatedAt(new \DateTime());
            $media->setAuthor($this->getUser());
            $media->setMediaId($movieId);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('media/movie-details.html.twig', [
            'movie' => $details,
            'credits' => $credits,
            'recommendations' => $recommendations->results,
            'movieGenres' => $movieGenres,
            'images' => $images,
            'videos' => $videos->results,
            'comments' => $commentRepository->findBy(['mediaId' => $movieId], ['created_at' => 'DESC']),
            'reviews' => $reviewRepository->findBy(['mediaId' => $movieId], ['created_at' => 'DESC']),
            'reviewForm' => $reviewForm->createView()
        ]);
    }
}