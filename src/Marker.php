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
      * ? makes more sense to write
      * Marker::img('path/to/img.jpg', 'An alternative text', ['width' => 34, 'height' => 34])
      * than
      * Marker::img(null, ['src' => 'path/to/img.jpg', 'alt' => 'An alternative text', 'width' => 34, 'height' => 34])
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

    public static function fas(string $icon, string $title = null, array $attributes = []): Element
    {
        $attributes['title'] ??= $title; // attributes take precedence
        $attributes['class'] = sprintf('fas fa-%s %s', $icon, $attributes['class'] ?? '');
        return new Element('i', '', $attributes);
    }

    public static function checkbutton(string $name, mixed $value, string $label, array $attributes = []): Element
    {
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }

        if (!isset($attributes['type'])) {
            $attributes['type'] = 'checkbox'; // default
        }

        if (isset($attributes['is_checked']) && $attributes['is_checked'] === true) { // for boolean checkbuttons
            $attributes['checked'] = 'checked';
            unset($attributes['is_checked']);
        }

        return
        Marker::div(
            Marker::label(
                Form::input($name, $value, $attributes) . Marker::span($label),
                ['for' => $attributes['id']]
            ),
            ['class' => 'checkbutton']
        );
    }
}
