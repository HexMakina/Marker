<?php

/**
 * HTML generator
 * Marker, hommage to Christian François Bouche-Villeneuve aka Chris Marker
 */

namespace HexMakina\Marker;

class Marker
{
    //::span('inner text', $attributes)
    public static function __callStatic($element_type, $arguments)
    {
        $i = 0;
        // first argument is the inner text
        $element_inner = $arguments[$i++] ?? null;
        // second argument, an array for HTML attributes
        $attributes = $arguments[$i++] ?? [];

        return new Element($element_type, $element_inner, $attributes);
    }

    // TODO labels should mandatory, accessibility
    // TODO implement all options of font-awesome
    public static function fas($icon, $title = null, $attributes = [])
    {
        $attributes['title'] = $attributes['title'] ?? $title; // attributes take precedence
        $attributes['class'] = sprintf('fas fa-%s %s', $icon, $attributes['class'] ?? '');
        return new Element('i', '', $attributes);
    }

    public static function checkbutton($field_name, $field_value, $field_label, $attributes = [])
    {
        if (!isset($attributes['id'])) {
            $attributes['id'] = $field_name;
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
                Form::input($field_name, $field_value, $attributes) . Marker::span($field_label),
                ['for' => $attributes['id']]
            ),
            ['class' => 'checkbutton']
        );
    }

    public static function img($src, $title, $attributes = [])
    {
        $attributes['src'] = $attributes['src'] ?? $src;
        $attributes['title'] = $attributes['title'] ?? $title;
        return new Element('img', null, $attributes);
    }

    public static function a($href, $label, $attributes = [])
    {
        $attributes['href'] = $attributes['href'] ?? $href;
        return new Element('a', $label, $attributes);
    }
}
