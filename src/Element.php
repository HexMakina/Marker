<?php

declare(strict_types=1);

namespace HexMakina\Marker;

/**
 * The Element class provides a simple and useful implementation for creating,  and generating valid HTML elements using PHP
 * The __toString() method of the Element class generates the HTML code for the element based on its tag, attributes, and inner content
 *
 * Additionally, the Element class provides a static shorthand syntax, using __callStatic() matching the name of the HTML Element to create
 *
 * The first argument is the inner content and the second, the attributes
 *
 * $abbr = Element::abbr('RTFM', ['title' => 'read the file manual']);
 * $p = Element:p('You reading this means you know about '.$abbr);
 * echo $p; // <p>You reading this means you know about <abbr title="read the file manual">RTFM</abbr></p>
 *
 * ::span('inner text', ['class' => 'd-block'])
 * ::p('lorem ipsum')
 * ::img(null, ['src' => 'path/to/jpeg.png', alt='hyper descriptive', 'width' => 100, 'height'=>100])
 * ::a('click here', ['href' => 'url/to/destination', 'class' => 'nav-link'])
 * ::a('anchor title', ['name' => 'anchor_name'])
 *
 * Regarding the attributes array, the keys represent the attribute names, and the values represent the attribute values.
 * If an attribute key is an integer, the corresponding attribute name and value are treated as a boolean attribute.
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

    // shorthand syntax for creating elements
    public static function __callStatic(string $tag, array $arguments): Element
    {
        return new Element($tag, $arguments[0] ?? null, $arguments[1] ?? []);
    }

    /**
     * Creates a new Element instance.
     * A null $inner means that the HTML element is a void element, ie br, hr, img, etc.
     * 
     * @param string $tag HTML tag, ie span, div, p, etc.
     * @param string|null $inner what will be inside the tag (null means void element)
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
     * If the value is null, empty string or empty array, the attribute is removed.
     * If the value is an array, it is imploded using a space character.
     * 
     * @param string $name The name of the attribute.
     * @param mixed $value The value of the attribute.
     * @return void
     */
    public function __set(string $name, $value = null)
    {
        // for class="class1 class2" syntax
        if (is_array($value)) 
        {
            // sets value to null to trigger unset() later
            $value = empty($value) ? null : implode(' ', $value);
        }

        // remove empty attributes
        if ($value === null || $value === '') 
        {
            unset($this->attributes[$name]);
        } 
        else 
        {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * Magic method to get the value of an attribute.
     *
     * @param string $name The name of the attribute.
     * @return string|null The value of the attribute, or null if it doesn't exist.
     */

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? '';
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

        if(!empty($this->attributes))
        {
            $attributes = array_walk($this->attributes, function(&$value, $key){
                $value = sprintf('%s="%s"', $key, $value);
            });

            $attributes = ' ' . implode(' ', $this->attributes);
        }

        return is_null($this->inner)
            ? sprintf('<%s%s/>', $this->tag, $attributes)
            : sprintf('<%s%s>%s</%s>', $this->tag, $attributes, $this->inner, $this->tag);
    }
}
