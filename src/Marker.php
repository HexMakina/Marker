<?php

/**
  * HTML generator
  * Marker, hommage to Christian François Bouche-Villeneuve aka Chris Marker
  */

declare(strict_types=1);

namespace HexMakina\Marker;

class Marker extends Element
{
    /**
      * ? smoother write
      * Marker::img('path/to/img.jpg', 'An alternative text', ['width' => 34, 'height' => 34])
      * than
      * Element::img(null, ['src' => 'path/to/img.jpg', 'alt' => 'An alternative text', 'width' => 34, 'height' => 34])
      */
    public static function img(string $src, string $alt, array $attributes = []): Element
    {
        $attributes['src'] ??= $src;
        $attributes['alt'] ??= $alt;
        $attributes['title'] ??= $alt;

        return new Element('img', null, $attributes);
    }

    /**
      * ? makes more sense to write
      * Marker::a('controller/task/id', 'Click here', ['class' => 'nav'])
      * than
      * Marker::a('Click here', ['href' => controller/task/id', 'class' => 'nav'])
      */
    public static function a(string $href, string $label, array $attributes = []): Element
    {
        $attributes['href'] ??= $href;

        return new Element('a', $label, $attributes);
    }
}
