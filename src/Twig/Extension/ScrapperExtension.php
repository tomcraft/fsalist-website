<?php


namespace App\Twig\Extension;


use App\Repository\Scrapper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

// https://symfony.com/doc/current/templating/twig_extension.html
class ScrapperExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('mediaURL', [$this, 'mediaURL']),
            new TwigFilter('genreName', [$this, 'genreName']),
        ];
    }

    public function mediaURL($relativeUrl, string $size = 'original')
    {
        if ($relativeUrl == null || $relativeUrl == '') {
            return '/img/covers/unknown.jpg';
        }
        return Scrapper::imageUrl($relativeUrl, $size);
    }

    public function genreName(int $genreId, string $lang)
    {
        return Scrapper::genres($lang)[$genreId];
    }
}