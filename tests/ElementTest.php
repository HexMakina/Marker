<?php

declare(strict_types=1);

use HexMakina\Marker\Element;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[CoversClass(Element::class)]
final class ElementTest extends TestCase
{
    // ────────────────────── construction & tag validation ──────────────────────

    #[DataProvider('validTagProvider')]
    public function testAcceptsValidTagNames(string $tag, string $expected): void
    {
        $this->assertSame($expected, (string) new Element($tag));
    }

    public static function validTagProvider(): iterable
    {
        yield 'single letter'       => ['i', '<i></i>'];
        yield 'classic'             => ['div', '<div></div>'];
        yield 'digit after letter'  => ['h1', '<h1></h1>'];
        yield 'custom element'      => ['my-widget', '<my-widget></my-widget>'];
        yield 'trailing hyphen'     => ['x-', '<x-></x->'];
        yield 'uppercase, non-void' => ['DIV', '<DIV></DIV>'];
    }

    #[DataProvider('invalidTagProvider')]
    public function testRejectsInvalidTagNames(string $tag): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Invalid HTML tag name "%s"', $tag));
        new Element($tag);
    }

    public static function invalidTagProvider(): iterable
    {
        yield 'empty string'     => [''];
        yield 'leading digit'    => ['1h'];
        yield 'leading hyphen'   => ['-div'];
        yield 'inner space'      => ['di v'];
        yield 'underscore'       => ['my_tag'];
        yield 'non-ascii'        => ['dïv'];
        yield 'markup injection' => ['div><script'];
        yield 'trailing newline' => ["div\n"];
    }

    // ──────────────────────────── void elements ────────────────────────────

    #[DataProvider('voidElementProvider')]
    public function testVoidElementsRenderWithoutClosingTag(string $tag): void
    {
        $this->assertSame("<{$tag}>", (string) new Element($tag));
    }

    public static function voidElementProvider(): iterable
    {
        $voids = [
            'area', 'base', 'br', 'col', 'embed', 'hr',
            'img', 'input', 'link', 'meta', 'param',
            'source', 'track', 'wbr',
        ];
        foreach ($voids as $tag) {
            yield $tag => [$tag];
        }
    }

    public function testVoidElementDiscardsInnerContent(): void
    {
        $this->assertSame('<br>', (string) new Element('br', 'silently dropped'));
    }

    public function testVoidDetectionIsCaseInsensitiveButPreservesCase(): void
    {
        $this->assertSame('<BR>', (string) new Element('BR'));
        $this->assertSame('<Input type="text">', (string) new Element('Input', null, ['type' => 'text']));
    }

    public function testVoidElementWithAttributes(): void
    {
        $this->assertSame(
            '<img src="test.jpg" alt="A test">',
            (string) new Element('img', null, ['src' => 'test.jpg', 'alt' => 'A test'])
        );
    }

    // ─────────────────────────── attribute rendering ───────────────────────────

    public function testBooleanAttributeRendersAsBareName(): void
    {
        $this->assertSame('<input checked>', (string) new Element('input', null, ['checked']));
    }

    public function testMixedAttributesPreserveDeclarationOrder(): void
    {
        $el = new Element('input', null, ['type' => 'radio', 'name' => 'gaga', 'checked', 'class' => 'a']);
        $this->assertSame('<input type="radio" name="gaga" checked class="a">', (string) $el);
    }

    #[DataProvider('attributeValueProvider')]
    public function testAttributeValueRendering(mixed $value, string $expected): void
    {
        $this->assertSame($expected, (string) new Element('div', null, ['data-v' => $value]));
    }

    public static function attributeValueProvider(): iterable
    {
        yield 'string'            => ['x', '<div data-v="x"></div>'];
        yield 'empty string'      => ['', '<div data-v=""></div>'];
        yield 'integer zero'      => [0, '<div data-v="0"></div>'];
        yield 'integer'           => [42, '<div data-v="42"></div>'];
        yield 'float'             => [1.5, '<div data-v="1.5"></div>'];
        yield 'array space-joins' => [['a', 'b'], '<div data-v="a b"></div>'];
        yield 'empty array'       => [[], '<div data-v=""></div>'];
        yield 'true renders bare' => [true, '<div data-v></div>'];
        // Pin: false and null collapse to `=""` rather than being omitted.
        // Careful with HTML boolean attributes: disabled="" IS disabled —
        // to truly remove one, unset() it instead of assigning false/null.
        yield 'false renders =""' => [false, '<div data-v=""></div>'];
        yield 'null renders =""'  => [null, '<div data-v=""></div>'];
    }

    public function testConstructorValidatesStringAttributeNames(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Element('div', null, ['on click' => 'x']);
    }

    public function testConstructorValidatesBooleanAttributeNames(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Element('div', null, ['no pe']);
    }

    // ─────────────────────────── magic property API ───────────────────────────

    public function testAttributeLifecycle(): void
    {
        $el = new Element('div');

        $this->assertFalse(isset($el->id));
        $this->assertNull($el->id);

        $el->id = 'main';
        $this->assertTrue(isset($el->id));
        $this->assertSame('main', $el->id);
        $this->assertSame('<div id="main"></div>', (string) $el);

        $el->id = 'other';
        $this->assertSame('other', $el->id);

        unset($el->id);
        $this->assertFalse(isset($el->id));
        $this->assertSame('<div></div>', (string) $el);
    }

    public function testBooleanAttributeToggle(): void
    {
        $el = new Element('option', 'Belgium');

        $el->selected = true;
        $this->assertSame('<option selected>Belgium</option>', (string) $el);

        unset($el->selected);
        $this->assertSame('<option>Belgium</option>', (string) $el);
    }

    public function testArrayAssignmentIsStoredSpaceJoined(): void
    {
        $el = new Element('div');
        $el->class = ['foo', 'bar'];

        $this->assertSame('foo bar', $el->class);
    }

    #[DataProvider('invalidAttributeNameProvider')]
    public function testRejectsInvalidAttributeNames(string $name): void
    {
        $el = new Element('div');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Invalid HTML attribute name "%s"', $name));
        $el->$name = 'x';
    }

    public static function invalidAttributeNameProvider(): iterable
    {
        yield 'leading digit'  => ['1st'];
        yield 'inner space'    => ['on click'];
        yield 'equals sign'    => ['a=b'];
        yield 'quote breakout' => ['x" onmouseover="alert(1)'];
        yield 'trailing newline' => ["class\n"];
    }

    // ─────────────────── escaping & the formatter contract ───────────────────

    public function testDefaultFormatterEscapesInnerAndAttributeValues(): void
    {
        $el = new Element('div', 'Hello & <World> "quoted" \'squoted\'', ['title' => 'a & "b"']);

        $this->assertSame(
            '<div title="a &amp; &quot;b&quot;">Hello &amp; &lt;World&gt; &quot;quoted&quot; &#039;squoted&#039;</div>',
            (string) $el
        );
    }

    /**
     * The proof that escaping works is not an entity-by-entity string match:
     * feed the rendered markup to a real HTML parser and demand the original
     * content back, with no elements smuggled in along the way.
     */
    #[DataProvider('hostileContentProvider')]
    #[RequiresPhpExtension('dom')]
    public function testEscapedOutputRoundTripsThroughAnHtmlParser(string $hostile): void
    {
        $html = (string) new Element('div', $hostile, ['title' => $hostile]);

        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_NOERROR);
        $div = $dom->getElementsByTagName('div')->item(0);

        $this->assertSame($hostile, $div->textContent);
        $this->assertSame($hostile, $div->getAttribute('title'));
        $this->assertSame(0, $dom->getElementsByTagName('script')->length);
        $this->assertSame(0, $dom->getElementsByTagName('img')->length);
    }

    public static function hostileContentProvider(): iterable
    {
        yield 'script tag'                        => ['<script>alert(1)</script>'];
        yield 'attribute breakout (double quote)' => ['" onmouseover="alert(1)'];
        yield 'attribute breakout (single quote)' => ["' onmouseover='alert(1)"];
        yield 'img onerror'                       => ['<img src=x onerror=alert(1)>'];
        yield 'entity soup'                       => ['&amp; &lt; &gt; &quot;'];
        yield 'unicode'                           => ['héllo wörld — ✓ 中文'];
    }

    public function testDefaultFormatterDoubleEncodesEntities(): void
    {
        // htmlspecialchars() default behaviour: pre-encoded input is encoded
        // again. Escape once, at render time — never feed encoded strings in.
        $this->assertSame('<p>&amp;amp;</p>', (string) new Element('p', '&amp;'));
    }

    public function testFalseFormatterEmitsEverythingRaw(): void
    {
        $el = new Element('div', 'a & <b>', ['data-raw' => 'x & "y"'], false);

        $this->assertSame('<div data-raw="x & "y"">a & <b></div>', (string) $el);
    }

    public function testDefaultFormatterPreventsNaiveNesting(): void
    {
        // without formatter:false, child markup is escaped, not embedded
        $inner = (string) Element::b('bold');

        $this->assertSame('<div>&lt;b&gt;bold&lt;/b&gt;</div>', (string) new Element('div', $inner));
    }

    public function testCustomFormatterAppliesToNamesValuesAndInner(): void
    {
        // ...but never to the tag itself
        $el = new Element('div', 'inner', ['class' => 'foo'], 'strtoupper');

        $this->assertSame('<div CLASS="FOO">INNER</div>', (string) $el);
    }

    public function testCustomFormatterCallSequence(): void
    {
        $seen = [];
        $spy = function ($v) use (&$seen) {
            $seen[] = $v;
            return $v;
        };

        (string) new Element('div', null, ['id' => 'x'], $spy);

        // name first, then value, then inner — null inner arrives as ''
        $this->assertSame(['id', 'x', ''], $seen);
    }

    public function testTrueFormatterSelectsTheDefaultFormatter(): void
    {
        $expected = '<div title="&quot;q&quot;">a &amp; b</div>';

        $this->assertSame($expected, (string) new Element('div', 'a & b', ['title' => '"q"'], true));
        $this->assertSame($expected, (string) new Element('div', 'a & b', ['title' => '"q"']));
    }

    // ────────────────────────────── static factory ──────────────────────────────

    public function testStaticFactoryMirrorsConstructor(): void
    {
        $expected = '<div id="i">a & b</div>';

        $this->assertSame($expected, (string) Element::div('a & b', ['id' => 'i'], false));
        $this->assertSame($expected, (string) new Element('div', 'a & b', ['id' => 'i'], false));

        $this->assertSame('<hr>', (string) Element::hr());
        $this->assertInstanceOf(Element::class, Element::div());
    }

    public function testStaticFactoryBuildsCustomElements(): void
    {
        $this->assertSame(
            '<fancy-button disabled>Push</fancy-button>',
            (string) Element::{'fancy-button'}('Push', ['disabled'])
        );
    }

    public function testStaticFactoryRejectsInvalidTag(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Element::{'not a tag'}();
    }

    public function testStaticFactoryIsStrictAboutInnerType(): void
    {
        // Element.php declares strict_types: an int inner is not coerced.
        // Cast before passing: Element::td((string) $count)
        $this->expectException(TypeError::class);
        Element::div(123);
    }

    // ─────────────────────────── behavioural pins ───────────────────────────

    public function testNullValuedAttributeIsInvisibleToIssetYetStillRenders(): void
    {
        $el = new Element('div');
        $el->id = null;

        // isset() is null-blind...
        $this->assertFalse(isset($el->id));
        // ...but the attribute was stored, and renders with an empty value
        $this->assertSame('<div id=""></div>', (string) $el);
    }

    public function testProtectedPropertiesAreShadowedByAttributes(): void
    {
        // tag, inner, attributes and formatter are protected: from outside,
        // assignment routes through __set into the attribute bag. It does
        // NOT change the element's actual tag or content.
        $el = new Element('div', 'original');
        $el->tag = 'span';
        $el->inner = 'replaced';

        $this->assertSame('span', $el->tag);
        $this->assertSame('<div tag="span" inner="replaced">original</div>', (string) $el);
    }

    // ──────────────────────── rendering & composition ────────────────────────

    public function testElementIsStringable(): void
    {
        $el = new Element('p', 'x');

        $this->assertInstanceOf(Stringable::class, $el);
        $this->assertSame('<p>x</p>', "{$el}");
    }

    public function testRenderingIsRepeatableAndReflectsMutation(): void
    {
        $el = new Element('div', 'x');

        $first = (string) $el;
        $this->assertSame($first, (string) $el); // rendering has no side effects

        $el->id = 'now';
        $this->assertSame('<div id="now">x</div>', (string) $el); // and stays live
    }

    public function testComposesNestedTreesViaRawFormatter(): void
    {
        $li = fn(string $s): string => (string) Element::li($s);
        $ul = new Element('ul', $li('one') . $li('two'), ['class' => 'menu'], false);

        $this->assertSame('<ul class="menu"><li>one</li><li>two</li></ul>', (string) $ul);
    }

    public function testLayeredEscapingComposesCorrectly(): void
    {
        // each leaf escapes its own content; the container, built with
        // formatter:false, embeds the already-safe fragments untouched
        $img = (string) Element::img(null, ['src' => 'cat.jpg', 'alt' => 'A cat']);
        $caption = (string) Element::figcaption('Miaou & friends');
        $figure = new Element('figure', $img . $caption, ['class' => ['card', 'pet']], false);

        $this->assertSame(
            '<figure class="card pet"><img src="cat.jpg" alt="A cat"><figcaption>Miaou &amp; friends</figcaption></figure>',
            (string) $figure
        );
    }
}
