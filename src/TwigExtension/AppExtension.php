<?php

namespace App\TwigExtension;

use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigTest;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getFilters()
    {
        return new TwigFilter('price', [$this, 'priceFilter']);
    }

    public function priceFilter($number)
    {
        return '$'.number_format($number, 2, '.', ',');
    }

    public function getGlobals(): array
    {
        return [
            'locale' => $this->locale
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest('like', static function ($object) {return $object instanceof LikeNotification;})
        ];
    }
}