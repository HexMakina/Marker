[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/HexMakina/Marker/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/HexMakina/Marker/?branch=main)
[![Maintainability](https://api.codeclimate.com/v1/badges/b2b950b6a92899acc876/maintainability)](https://codeclimate.com/github/HexMakina/Marker/maintainability)
<img src="https://img.shields.io/badge/PSR-4-brightgreen" alt="PSR-4 Compliant" />
<img src="https://img.shields.io/badge/PSR-12-brightgreen" alt="PSR-12 Compliant" />
<img src="https://img.shields.io/badge/PHP-7.4-brightgreen" alt="PHP 7.4 Required" />
[![Latest Stable Version](http://poser.pugx.org/hexmakina/marker/v)](https://packagist.org/packages/hexmakina/marker)
[![License](http://poser.pugx.org/hexmakina/marker/license)](https://packagist.org/packages/hexmakina/marker)

# Marker
HTML Generation classes
Hommage to Christian François Bouche-Villeneuve aka Chris Marker


# Class Element

The Element class provides a simple and useful implementation for creating,  and generating valid HTML elements using PHP
The __toString() method of the Element class generates the HTML code for the element based on its tag, attributes, and content

Additionally, the Element class provides a static shorthand syntax, using __callStatic() matching the name of the HTML Element to create

The first argument is the content, the second are the attributes

$abbr = Element::abbr('RTFM', ['title' => 'read the file manual']);
$p = Element:p('You reading this means you know about '.$abbr);
echo $p; // <p>You reading this means you know about <abbr title="read the file manual">RTFM</abbr></p>

::span('inner text', ['class' => 'd-block'])
::p('lorem ipsum')
::img(null, ['src' => 'path/to/jpeg.png', alt='hyper descriptive', 'width' => 100, 'height'=>100])
::a('click here', ['href' => 'url/to/destination', 'class' => 'nav-link'])
::a('anchor title', ['name' => 'anchor_name'])

Regarding the attributes array, the keys represent the attribute names, and the values represent the attribute values.
If an attribute key is an integer, the corresponding attribute name and value are treated as a boolean attribute.


Example usage:

```
$element = new Element('section', 'Hello World!', [
    'id' => 'publication',
    'class' => 'container',
    'data-toggle' => 'modal',
    'data-target' => '#myModal',
]);

<section id="publication" class="container" data-toggle="modal" "data-target"="#myModal">Hello World!</section>
```

Or, with void element and boolean attributes:

$element = new Element('input', null, [
     'type' => 'checkbox',
     'checked',
     'disabled',
     'class' => 'checkbutton'
]);

<input type="checkbox" checked disabled class="checkbutton"/>


# Class Marker

# Class Form