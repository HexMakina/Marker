[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/HexMakina/Marker/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/HexMakina/Marker/?branch=main)
[![Maintainability](https://api.codeclimate.com/v1/badges/b2b950b6a92899acc876/maintainability)](https://codeclimate.com/github/HexMakina/Marker/maintainability)
<img src="https://img.shields.io/badge/PSR-4-brightgreen" alt="PSR-4 Compliant" />
<img src="https://img.shields.io/badge/PSR-12-brightgreen" alt="PSR-12 Compliant" />
<img src="https://img.shields.io/badge/PHP-7.4-brightgreen" alt="PHP 7.4 Required" />
[![Latest Stable Version](http://poser.pugx.org/hexmakina/marker/v)](https://packagist.org/packages/hexmakina/marker)
[![License](http://poser.pugx.org/hexmakina/marker/license)](https://packagist.org/packages/hexmakina/marker)

# Marker

HTML generation classes for PHP.

Hommage to Christian François Bouche-Villeneuve aka Chris Marker.

## Installation

```bash
composer require hexmakina/marker
```

All classes live in the `HexMakina\Marker` namespace:

```php
use HexMakina\Marker\Element;
use HexMakina\Marker\Marker;
use HexMakina\Marker\Form;
```

## Contents

- [`Element`](#class-element) — the core HTML builder.
- [`Marker`](#class-marker) — ergonomic shortcuts for `<img>` and `<a>`.
- [`Form`](#class-form) — labelled form fields.
- [Accessibility helper](#accessibility-helper-removed) — note on the removed `WCAGElement`.

---

# Class Element

`Element` builds a single HTML element. Its `__toString()` renders the tag, its
attributes, and its inner content. You can construct it directly or use the
static shorthand.

```php
$element = new Element('section', 'Hello World!', [
    'id'          => 'publication',
    'class'       => 'container',
    'data-toggle' => 'modal',
    'data-target' => '#myModal',
]);

echo $element;
// <section id="publication" class="container" data-toggle="modal" data-target="#myModal">Hello World!</section>
```

## Static shorthand

Any undefined static call is treated as a tag name. The first argument is the
inner content, the second the attributes array:

```php
echo Element::span('inner text', ['class' => 'd-block']);
// <span class="d-block">inner text</span>

echo Element::p('lorem ipsum');
// <p>lorem ipsum</p>
```

## Escaped text versus trusted HTML

**By default, inner content and attribute values are escaped** with
`htmlspecialchars(..., ENT_QUOTES)`. This is the safe default: any value coming
from user input is neutralised.

```php
echo new Element('p', '<script>alert(1)</script>');
// <p>&lt;script&gt;alert(1)&lt;/script&gt;</p>
```

The escaping behaviour is controlled by the **fourth constructor argument,
`$formatter`**:

| `$formatter` value        | Effect                                                        |
| ------------------------- | ------------------------------------------------------------- |
| `null` (default) or omitted | Escapes with `htmlspecialchars(..., ENT_QUOTES)`.          |
| `false`                   | Trusted mode: content and attributes are emitted **verbatim**. |
| a `callable`              | Custom formatter applied to every value.                      |

Use `false` **only** when the content is already safe markup that you produced
yourself. This is what lets you nest elements:

```php
$abbr = new Element('abbr', 'RTFM', ['title' => 'read the manual']);

// Trusted parent: the child markup survives intact.
echo new Element('p', 'See ' . $abbr, [], false);
// <p>See <abbr title="read the manual">RTFM</abbr></p>
```

> ⚠️ **Nesting pitfall.** If you concatenate a child element into a parent that
> uses the *default* (escaping) formatter, the child's tags are escaped:
>
> ```php
> echo new Element('p', 'See ' . $abbr);
> // <p>See &lt;abbr title=&quot;read the manual&quot;&gt;RTFM&lt;/abbr&gt;</p>
> ```
>
> To nest safely, pass `false` as the formatter on the **parent** (as above), so
> the parent trusts the already-escaped child. Do not use `false` on an element
> whose *own* content is untrusted user data.

## Void elements

Void elements (`area`, `base`, `br`, `col`, `embed`, `hr`, `img`, `input`,
`link`, `meta`, `param`, `source`, `track`, `wbr`) are rendered without a
closing tag. Pass `null` as the content:

```php
echo new Element('img', null, ['src' => 'a.jpg', 'alt' => 'x']);
// <img src="a.jpg" alt="x">

echo new Element('br');
// <br>
```

Any content passed to a void element is ignored — a void tag never has a body.

## Boolean attributes

In the attributes array, an entry with an **integer key** (i.e. a bare value
with no `=> `) is rendered as a boolean attribute — just its name, no value.
String-keyed entries render as `name="value"`.

```php
echo new Element('input', null, [
    'type' => 'checkbox',
    'checked',            // integer key -> boolean attribute
    'disabled',           // integer key -> boolean attribute
    'class' => 'c',       // string key  -> name="value"
]);
// <input type="checkbox" checked disabled class="c">
```

## Attribute and tag name validation

Tag and attribute names must match `^[a-zA-Z][a-zA-Z0-9-]*$`. An invalid name
throws `\InvalidArgumentException`:

```php
new Element('123');            // throws: Invalid HTML tag name "123"
$el = new Element('div');
$el->{'bad attr'} = 'x';       // throws: Invalid HTML attribute name "bad attr"
```

Attributes can also be read, set, checked, and removed as properties:

```php
$el = new Element('div', 'body', ['id' => 'main']);
$el->class = 'container';     // set
echo $el->id;                 // "main"
echo isset($el->id) ? 'y':'n';// "y"
unset($el->class);            // remove
```

---

# Class Marker

`Marker` extends `Element` with two shortcuts whose argument order reads more
naturally than the generic form.

```php
// <img> — src and alt come first; alt is copied to title if not given.
echo Marker::img('a.jpg', 'alt text', ['width' => 100]);
// <img width="100" src="a.jpg" alt="alt text" title="alt text">

// <a> — href first, then label.
echo Marker::a('/dest', 'Click', ['class' => 'nav']);
// <a class="nav" href="/dest">Click</a>
```

Both accept an optional fourth `$formatter` argument with the same meaning as on
`Element` (`null` to escape, `false` to trust).

---

# Class Form

`Form` builds form controls, each optionally paired with a `<label>`. Field
**values and attributes are always escaped**; only the assembled label+field
markup is treated as trusted so its own tags are not double-escaped.

## Inputs

```php
echo Form::input('email', 'a@b.com', ['label' => 'Email']);
// <label for="email">Email</label><input name="email" value="a@b.com" type="text" id="email">
```

The `id` defaults to the field `name`. If there is no name/id, no empty `id`
attribute is emitted.

Any input type is available through the static shorthand — the method name
becomes the `type`:

```php
echo Form::hidden('token', 'abc');   // <input type="hidden" ...>
echo Form::date('due', '2026-01-01');
echo Form::datetime('when', '...');  // type becomes "datetime-local"
echo Form::password('pw', 'secret'); // value is always blanked for passwords
```

A `type` you pass explicitly is respected — including on disabled fields:

```php
echo Form::input('agree', '1', ['type' => 'checkbox', 'disabled']);
// <input type="checkbox" disabled name="agree" value="1" id="agree">
```

## Form labels

A `label` entry in the attributes array attaches a label. Two layouts:

**Sibling label (default)** — a `<label for="id">` next to the field:

```php
echo Form::input('email', 'a@b.com', ['label' => 'Email']);
// <label for="email">Email</label><input ... id="email">
```

**Wrapping label** — set `label-wrap` to nest the field inside the `<label>`.
This is also used automatically when the field has no `id` to bind a sibling
label to:

```php
echo Form::input('agree', '1', ['type' => 'checkbox', 'label' => 'I agree', 'label-wrap' => true]);
// <label>I agree<input type="checkbox" name="agree" value="1" id="agree"></label>
```

The internal keys `label`, `label-wrap`, and `tag` are stripped before
rendering and never appear as HTML attributes.

## Textarea and select

```php
echo Form::textarea('bio', 'Hello', ['label' => 'Bio']);
// <label for="bio">Bio</label><textarea name="bio" id="bio">Hello</textarea>

echo Form::select('color', [1 => 'Red', 2 => 'Blue'], 2, ['label' => 'Color']);
// <label for="color">Color</label><select name="color" id="color"><option value="1">Red</option><option value="2" selected="selected">Blue</option></select>
```

The selected option is matched by a strict comparison of the string forms of the
selected value and each option key, so a numeric-string selected value still
matches an integer array key without loose-typing surprises.

## Buttons

```php
echo Form::submit('save', 'Save');
// <button type="submit" id="save">Save</button>

echo Form::submit('save', 'Save', ['tag' => 'input']);
// <input type="submit" id="save" value="Save">   (rendered as a void element)

echo Form::button('Cancel', ['class' => 'btn']);
// <button class="btn">Cancel</button>

echo Form::legend('Account');
// <legend>Account</legend>
```

---

# Accessibility helper (removed)

Earlier versions shipped a `WCAGElement` class that enforced a handful of
accessibility rules (required `alt` on `<img>`/`<area>`, `<figcaption>` inside
`<figure>`, `title` on `<iframe>`, `href` on `<a>`, `lang` on `<html>`, and so
on). **This class has been removed and is no longer part of the package.**

`tests/WCAGElementTest.php` still references the removed class and will fail
until it is deleted or the class is deliberately reintroduced. Do not rely on
`WCAGElement` — it does not exist in `src/`.
