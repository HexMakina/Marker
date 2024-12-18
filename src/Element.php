<?php

declare(strict_types=1);

namespace HexMakina\Marker;

/**
 * The Element class provides a simple and useful implementation for creating, and generating valid HTML elements using PHP
 * The __toString() method of the Element class generates the HTML code for the element based on its tag, attributes, and inner content
 *
 *
 * Example usage:
 *
 * ```
 * $element = new Element('section', 'Hello World!', [
 *     'id' => 'publication',
 *     'class' => 'container',
 *     'data-toggle' => 'modal',
 *     'data-target' => '#myModal',
 * ]);
 *
 * <section id="publication" class="container" data-toggle="modal" "data-target"="#myModal">Hello World!</section>
 * ```
 *
 * Or, with void element and boolean attributes:
 *
 * $element = new Element('input', null, [
 *      'type' => 'checkbox',
 *      'checked',
 *      'disabled',
 *      'class' => ['button', 'checkbutton']
 * ]);
 *
 * <input type="checkbox" checked="checked" disabled="disabled" class="button checkbutton"/>
 *
 */

class Element
{
    protected string $tag;
    protected ?string $inner;
    protected array $attributes = [];

    // Constants for sprintf formats
    public const FORMAT_TAG_VOID = '<%s%s/>';   // <br/>, <img src="path/to/jpeg.png" alt="hyper descriptive" width="100" height="100"/>
    public const FORMAT_TAG = '<%s%s>%s</%s>';  // <span class="bar">foo</span>, <div class="bar">foo</div>
    public const FORMAT_ATTRIBUTE = ' %s="%s"'; // id="publication", class="container", data-toggle="modal"

    /**
     * Creates a new Element instance using a static method call.
     *
     * Element::span('foo', ['class' => 'bar']) gives the same result as 
     * new Element('span', 'foo', ['class' => 'bar'])
     * 
     * ex: Element::div('foo', ['class' => 'bar'])
     * ex: Element::img(null, ['src' => 'path/to/jpeg.png', 'alt' => 'hyper descriptive', 'width' => 100, 'height' => 100])
     * ex: Element::a('click here', ['href' => 'url/to/destination'])

     * @param string $tag The HTML tag for the element.
     * @param mixed $content The content of the element, null for void elements.
     * @param array $attributes The attributes of the element.
     * @return Element The newly created Element instance.
     */
    public static function __callStatic(string $tag, array $arguments): self
    {
        return new self($tag, $arguments[0] ?? null, $arguments[1] ?? []);
    }


    /**
     * Element constructor.
     * 
     * A null $inner means that the HTML element is a void element, ie br, hr, img, etc.
     * ex: new Element('span', 'foo', ['class' => 'bar'])
     * ex: new Element('div', 'foo', ['class' => 'bar'])
     * ex: new Element('img', null, ['src' => 'path/to/jpeg.png', 'alt'='hyper descriptive', 'width' => 100, 'height' => 100])
     * ex: new Element('a', 'click here', ['href' => 'url/to/destination'])
     * 
     * @param string $tag HTML tag, ie span, div, p, etc.
     * @param ?string $inner what will be inside the tag (null means void element)
     * @param mixed[] $attributes An array of attributes for the element (optional).
     * 
     */
    public function __construct(string $tag, string $inner = null, array $attributes = [])
    {
        $this->tag = $tag;
        $this->inner = $inner;

        foreach ($attributes as $name => $value) {

            if (is_int($name)) {
                $name = $value;
            }
            $this->$name = $value;
        }
    }

    /**
     * Magic method to set element attributes.
     *
     * If the value is empty (but not '0'), the attribute is removed.
     * If the value is an array, it is imploded using a space character.
     *
     * @param string $name The name of the attribute.
     * @param mixed $value The value of the attribute.
     * 
     */
    public function __set(string $name, $value = null)
    {
        $this->attributes[$name] = is_array($value) ? implode(' ', $value) : $value;
    }

    /**
     * Magic method to get the value of an attribute.
     * 
     * If the attribute doesn't exist, it returns an empty string.
     * Empty string because the class aims for ease of use, *not* strictness, for once.     
     *
     * @param string $name The name of the attribute.
     * @return string The value of the attribute, or '' if it doesn't exist.
     * 
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? '';
    }

    /**
     * Magic method to check if an attribute exists.
     *
     * @param string $name The name of the attribute.
     * @return bool True if the attribute exists, false otherwise.
     * 
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Magic method to unset an attribute.
     *
     * @param string $name The name of the attribute.
     * 
     */
    public function __unset(string $name)
    {
        unset($this->attributes[$name]);
    }

    /**
     * This method returns the string representation of the Element object by formatting the tag, attributes, and inner
     * of the element based on whether it is a void element or not. If the element is a void element, it returns the tag and
     * attributes only. Otherwise, it returns the tag, attributes, inner, and closing tag.
     *
     * @return string The string representation of the Element object.
     */
    public function __toString(): string
    {
        $attributes = '';
        foreach ($this->attributes as $name => $value) {
            $value = htmlspecialchars($value, ENT_QUOTES);
            $attributes .= sprintf(self::FORMAT_ATTRIBUTE, $name, $value);
        }

        return $this->inner === null
            ? sprintf(self::FORMAT_TAG_VOID, $this->tag, $attributes)
            : sprintf(self::FORMAT_TAG, $this->tag, $attributes, $this->inner, $this->tag);
    }
}
