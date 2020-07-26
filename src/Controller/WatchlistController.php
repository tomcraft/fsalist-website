<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Watchlist;
use App\Entity\WatchlistMedia;
use App\Repository\Scrapper;
use App\Security\AppAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WatchlistController extends AbstractController
{

    private function getOrCreateWatchlist(User $user) {
        $watchlist = $user->getMainWatchlist();
        $entityManager = $this->getDoctrine()->getManager();
        if (!$watchlist->getId()) {
            $entityManager->persist($watchlist);
            $entityManager->flush();
            $id = $watchlist->getId();
            $watchlist->setShareId(md5("$id"));
            $entityManager->flush();
        }
        return $watchlist;
    }

    private function redirectMedia($mediaType, $mediaId) {
        if ($mediaType == 'movie') {
            return $this->redirectToRoute('movie-details', ['movieId' => $mediaId]);
        } else if ($mediaType == 'tv') {
            return $this->redirectToRoute('tv-show-details', ['tvShowId' => $mediaId]);
        }
        return new Response('', 201);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/watchlist", name="watchlist")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        /** @var $user User */
        $user = $this->getUser();
        $locale = $request->getLocale();
        $watchlist = $this->getOrCreateWatchlist($user);
        foreach ($watchlist->getMedias() as $media) {
            $mediaData = $media->getMediaType() == 'movie' ? Scrapper::movieDetails($media->getMediaId(), $locale) : Scrapper::tvShowDetails($media->getMediaId(), $locale);
            $media->setData($mediaData);
        }

        return $this->render('watchlist/index.html.twig', [ 'medias' => $watchlist->getMedias() ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/watchlist/add", name="watchlist-media-add", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addMedia(Request $request)
    {
        $mediaType = $request->get('mediaType');
        $mediaId = $request->get('mediaId');
        /** @var $user User */
        $user = $this->getUser();
        $watchlist = $this->getOrCreateWatchlist($user);

        $entityManager = $this->getDoctrine()->getManager();

        $media = new WatchlistMedia();
        $media->setMediaType($mediaType);
        $media->setMediaId($mediaId);
        $watchlist->addMedia($media);
        $entityManager->persist($media);
        $entityManager->flush();

        return $this->redirectMedia($mediaType, $mediaId);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/watchlist/remove", name="watchlist-media-remove", methods={"POST"})
     * @param Request $request
     * @param string $mediaType
     * @param int $mediaId
     * @return RedirectResponse|Response
     */
    public function removeMedia(Request $request)
    {
        $mediaType = $request->get('mediaType');
        $mediaId = $request->get('mediaId');
        /** @var $user User */
        $user = $this->getUser();
        $watchlist = $this->getOrCreateWatchlist($user);
        $watchlistMediaRepo = $this->getDoctrine()->getRepository(WatchlistMedia::class);
        $watchlistMedia = $watchlistMediaRepo->findOneBy([
                "watchlist" => $watchlist,
                "mediaId" => $mediaId,
                "mediaType" => $mediaType
        ]);
        if ($watchlistMedia) {
            $watchlist->removeMedia($watchlistMedia);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return $this->redirectMedia($mediaType, $mediaId);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/watchlist/seen", name="watchlist-media-seen", methods={"POST"})
     * @param Request $request
     * @param string $mediaType
     * @param int $mediaId
     * @return RedirectResponse|Response
     */
    public function setSeenMedia(Request $request) {
        return $this->_setSeenMedia($request, true);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/watchlist/unseen", name="watchlist-media-unseen", methods={"POST"})
     * @param Request $request
     * @param string $mediaType
     * @param int $mediaId
     * @return RedirectResponse|Response
     */
    public function setUnSeenMedia(Request $request) {
        return $this->_setSeenMedia($request, false);
    }

    private function _setSeenMedia(Request $request, bool $seen)
    {
        $mediaType = $request->get('mediaType');
        $mediaId = $request->get('mediaId');
        /** @var $user User */
        $user = $this->getUser();
        $watchlist = $this->getOrCreateWatchlist($user);
        $watchlistMediaRepo = $this->getDoctrine()->getRepository(WatchlistMedia::class);
        $watchlistMedia = $watchlistMediaRepo->findOneBy([
                "watchlist" => $watchlist,
                "mediaId" => $mediaId,
                "mediaType" => $mediaType
        ]);
        if ($watchlistMedia) {
            $watchlistMedia->setSeen($seen);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new Response('', 201);
    }

}
