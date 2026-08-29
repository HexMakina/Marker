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
 * 
 * - audio(string $src, array $attributes = []): string
 *   Enforces the 'controls' attribute for <audio> tags. Throws an InvalidArgumentException if the 'src' attribute is empty.
 * 
 * - video(string $src, array $attributes = []): string
*    Enforces the 'controls' attribute for <video> tags. Throws an InvalidArgumentException if the 'src' attribute is empty.
 * 
 * - iframe(string $src, string $title, array $attributes = []): string
 *  Enforces the 'title' attribute for <iframe> tags. Throws an InvalidArgumentException if the 'title' attribute is empty.
 * 
 * - a(string $href, string $content, array $attributes = []): string
 *  Enforces the 'href' attribute for <a> tags. Throws an InvalidArgumentException if the 'href' attribute is empty.
 * 
 * - area(string $alt, array $attributes = []): string
 * Enforces the 'alt' attribute for <area> tags. Throws an InvalidArgumentException if the 'alt' attribute is empty.
 * 
 * - input(string $type, string $name, array $attributes = []): string
 * Enforces the 'type' and 'name' attributes for <input> tags. Throws an InvalidArgumentException if the 'type' or 'name' attribute is empty.
 *  
 * - select(string $name, array $options, array $attributes = []): string
 * Enforces the 'name' attribute for <select> tags. Throws an InvalidArgumentException if the 'name' attribute is empty.
 * 
 * - textarea(string $name, array $attributes = []): string
 * Enforces the 'name' attribute for <textarea> tags. Throws an InvalidArgumentException if the 'name' attribute is empty.
 * 
 * - label(string $for, string $content, array $attributes = []): string
 * Enforces the 'for' attribute for <label> tags. Throws an InvalidArgumentException if the 'for' attribute is empty.
 * 
 * - html(string $content, array $attributes = []): string
 *  Enforces content being passed to the <html> tag. Throws an InvalidArgumentException if the content is empty.
 *   Enforces the 'lang' attribute for <html> tags. Throws an InvalidArgumentException if the 'lang' attribute is empty.
 * 
 * - th(string $scope, string $content, array $attributes = []): string
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

    /**
     * <audio>
     * Required Attribute: controls
     * Ensures users can control playback (pause, stop, etc.).
     * WCAG 2.1 Level A: 1.2.1 Audio-Only and Video-Only (Prerecorded)
     */
    public static function audio(string $src, $attributes = [])
    {
        if(empty($src)) {
            throw new \InvalidArgumentException("Source attribute is required");
        }

        $attributes['src'] = $src;
        $attributes['controls'] ??= 'controls';
        return parent::audio(null, $attributes);
    }

    /**
     * <video>
     * Required Attribute: controls
     * Ensures users can control playback (pause, stop, etc.).
     * WCAG 2.1 Level A: 1.2.1 Audio-Only and Video-Only (Prerecorded)
     */
    public static function video(string $src, $attributes = [])
    {
        if (empty($src)) {
            throw new \InvalidArgumentException("Source attribute is required");
        }
        $attributes['src'] = $src;
        $attributes['controls'] ??= 'controls';
        return parent::video(null, $attributes);
    }

    /**
     * <iframe>
     * Required Attribute: title
     * WCAG 2.1 Level A: 2.4.1 Bypass Blocks
     */
    public static function iframe(string $src, string $title, $attributes = [])
    {
        if(empty($title)) {
            throw new \InvalidArgumentException("Iframe title is required");
        }

        $attributes['src'] = $src;
        $attributes['title'] = $title;
        return parent::iframe(null, $attributes);
    }
    
    /**
     * <a>
     * Required Attribute: href
     * WCAG 2.1 Level A: 2.4.4 Link Purpose (In Context)
     */
    public static function a(string $href, string $content, $attributes = [])
    {
        if(empty($href)) {
            throw new \InvalidArgumentException("Anchor href is required");
        }

        $attributes['href'] = $href;
        return parent::a($content, $attributes);
    }

    /**
     * <area>
     * Required Attribute: alt
     * WCAG 2.1 Level A: 1.1.1 Non-text Content
     */
    public static function area(string $alt, $attributes = [])
    {
        if(empty($alt)) {
            throw new \InvalidArgumentException("Area alt is required");
        }
        $attributes['alt'] = $alt;
        return parent::area(null, $attributes);
    }

    /**
     * <input>
     * Required Attribute: type
     * WCAG 2.1 Level A: 4.1.2 Name, Role, Value
     */
    public static function input(string $type, string $name, $attributes = [])
    {
        if(empty($type)) {
            throw new \InvalidArgumentException("Input type is required");
        }

        if(empty($name)) {
            throw new \InvalidArgumentException("Input name is required");
        }

        $attributes['name'] = $name;
        $attributes['type'] = $type;

        if(self::inputIsRequired($attributes)) {
            $attributes['aria-required'] = 'true';
        }

        return parent::input(null, $attributes);
    }

    private static function inputIsRequired($attributes): bool
    {
        return array_key_exists('required', $attributes) && $attributes['required'] === true
            || in_array('required', $attributes);
    }
    /**
     * <select>
     * Required Attribute: name
     * WCAG 2.1 Level A: 4.1.2 Name, Role, Value
     */
    public static function select(string $name, array $options, $attributes = [])
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Select name is required");
        }
        $attributes['name'] = $name;
        $string_options = '';
        foreach($options as $value => $label) {
            $string_options.= parent::option($label, ['value' => $value]);
        }
        return parent::select($string_options, $attributes, function ($value) {
            return $value;
        });
    }

    /**
     * <textarea>
     * Required Attribute: name
     * WCAG 2.1 Level A: 4.1.2 Name, Role, Value
     */
    public static function textarea(string $name, $attributes = [])
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Textarea name is required");
        }
        $attributes['name'] = $name;

        return parent::textarea(null, $attributes);
    }

    /**
     * <label>
     * Required Attribute: for
     * WCAG 2.1 Level A: 4.1.2 Name, Role, Value
     */
    public static function label(string $for, string $content, $attributes = [])
    {
        $attributes['for'] = $for;
        return parent::label($content, $attributes);
    }

    /**
     * <html>
     * Required Attribute: lang
     * WCAG 2.1 Level A: 3.1.1 Language of Page
     */
    public static function html(string $content, $attributes = [])
    {
        if(empty($content)) {
            throw new \InvalidArgumentException("HTML content is required");
        }

        if(empty($attributes['lang'])) {
            throw new \InvalidArgumentException("Language attribute is required");
        }
        return parent::html($content, $attributes);
    }

    /**
     * <th>
     * Required Attribute: scope
     * WCAG 2.1 Level A: 1.3.1 Info and Relationships
     */
    public static function th(string $scope, string $content, $attributes = [])
    {
        if(empty($scope)) {
            throw new \InvalidArgumentException("Scope attribute is required");
        }

        $attributes['scope'] = $scope;
        return parent::th($content, $attributes);
    }
    
}