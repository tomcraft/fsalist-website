<?php


namespace App\Twig\Extension;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

// https://symfony.com/doc/current/templating/twig_extension.html
class GravatarExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('gravatar', [$this, 'getGravatar']),
        ];
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param integer $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     *
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public function getGravatar( $email, $s = 80, $d = 'mp', $r = 'g' ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        return $url;
    }

}