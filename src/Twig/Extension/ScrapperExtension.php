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
        ];
    }

    public function mediaURL($relativeUrl, string $size = 'original')
    {
        if ($relativeUrl == null || $relativeUrl == '') {
            $num = random_int(1, 6);
            return "/img/covers/unknown-$num.jpg";
        }
        return Scrapper::imageUrl($relativeUrl, $size);
    }
}