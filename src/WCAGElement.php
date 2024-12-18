<?php

/**
 * Class WCAGElement
 * 
 * This class extends the Element class and provides methods to enforce WCAG (Web Content Accessibility Guidelines) compliance for certain HTML elements.
 * 
 * Methods:
 * 
 * - img(string $src, string $alt, array $attributes = []): string
 *   Enforces the 'alt' attribute for <img> tags. Throws an InvalidArgumentException if the 'alt' attribute is empty.
 * 
 * - figure(string $content, string $caption, array $attributes = []): string
 *   Enforces the presence of a <figcaption> inside a <figure>. Throws an InvalidArgumentException if the caption is empty.
 * 
 * - button(string $content, array $attributes = []): string
 *   Enforces that the button has content. Throws an InvalidArgumentException if the content is empty.
 */

namespace HexMakina\Marker;

class WCAGElement extends Element
{
    /**
     * Enforce 'alt' attribute for <img> tags.
     * WCAG 2.1 Level A: 1.1.1 Non-text Content
     * - Describes the image for screen readers.
     * - Must be empty (alt="") if the image is decorative.
     * 
     * @param string $src Image source
     * @param string $alt Alternative text (required, leave empty for decorative images
     * @param array  $attributes Additional attributes
     * @return string
     */
    public static function img(string $src, string $alt='', $attributes = [])
    {
        $attributes['src'] = $src;
        $attributes['alt'] = $alt;
        return parent::img(null, $attributes);
    }

    /**
     * Enforce <figcaption> inside <figure>.
     * 
     * @param string $content Content inside the figure
     * @param string $caption Caption text (required)
     * @param array  $attributes Additional attributes for figure
     * @return string
     */
    public static function figure(string $content, string $caption, $attributes = [])
    {
        if (empty($caption)) {
            throw new \InvalidArgumentException("The <figcaption> is required inside <figure>.");
        }

        $content .= parent::figcaption($caption);
        $attributes['role'] = 'figure';
        return parent::figure($content, $attributes, function ($value) {
            return $value;
        });
    }

    /**
     * General method to create a <button> tag with enforced content.
     * 
     * @param string $content Button text/content (required)
     * @param array  $attributes Additional attributes
     * @return string
     */
    public static function button(string $content, $attributes = [])
    {
        if (empty($content)) {
            throw new \InvalidArgumentException("Button content is required.");
            
        }
        $attributes['role'] ??= 'button';
        return parent::button($content, $attributes);
    }
}