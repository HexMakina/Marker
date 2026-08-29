<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HexMakina\Marker\Element;

final class ElementTest extends TestCase
{
    public function testCanBeCreatedFromValidTagName(): void
    {
        $this->assertInstanceOf(
            Element::class,
            new Element('div')
        );
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            '<div>test</div>',
            Element::div('test')->__toString()
        );
    }

    public function testVoidElement(): void
    {
        $this->assertEquals(
            '<hr/>',
            Element::hr()->__toString()
        );
    }

    public function testVoidElementWithAttribute(): void
    {
        $this->assertEquals(
            '<img src="test.jpg"/>',
            Element::img(null, ['src' => 'test.jpg'])->__toString()
        );

        $this->assertEquals(
            '<img src="test.jpg"/>',
            (new Element('img', null, ['src' => 'test.jpg']))->__toString()
        );
    }

    public function testCanHaveBooleanAttribute(): void
    {
        $element = new Element('input', null, ['checked']);

        $this->assertEquals(
            '<input checked/>',
            $element->__toString()
        );
    }

    public function testCanHaveMixedAttributes(): void
    {
        $element = new Element('input', null, ['type' => 'radio', 'name' => 'gaga', 'checked']);
        $this->assertEquals(
            '<input type="radio" name="gaga" checked/>',
            $element->__toString()
        );

        $element->class = 'test';
        $this->assertEquals(
            '<input type="radio" name="gaga" checked class="test"/>',
            $element->__toString()
        );

        $element->class = ['foo', 'bar'];
        $this->assertEquals(
            '<input type="radio" name="gaga" checked class="foo bar"/>',
            $element->__toString()
        );
    }

    public function testCanHaveStringClassAttribute(): void
    {
        $element = new Element('div', 'with class', ['class' => 'test']);

        $this->assertEquals(
            '<div class="test">with class</div>',
            $element->__toString()
        );
    }

    public function testCanHaveArrayClassAttribute(): void
    {
        $element = new Element('div', 'Hello, World!', ['class' => ['foo', 'bar']]);
        $this->assertEquals(
            '<div class="foo bar">Hello, World!</div>',
            $element->__toString()
        );
    }

    public function testCanHaveMultipleAttributes(): void
    {
        $element = new Element('div', 'Hello, World!', ['class' => ['foo', 'bar'], 'id' => 'test']);

        $this->assertEquals(
            '<div class="foo bar" id="test">Hello, World!</div>',
            $element->__toString()
        );
    }

    public function testContentHasSpecialCaracters(): void
    {
        $element = new Element('div', 'Hello, World! & < > " \' ', ['class' => ['foo', 'bar'], 'id' => 'test']);

        $this->assertEquals(
            '<div class="foo bar" id="test">Hello, World! &amp; &lt; &gt; &quot; &#039; </div>',
            $element->__toString()
        );

        $element = new Element('div', 'Hello, World! & < > " \' ', ['class' => [
            'foo',
            'bar'
        ], 'id' => 'test', 'data-attr' => 'Hello, World! & < > " \' ']);

        $this->assertEquals(
            '<div class="foo bar" id="test" data-attr="Hello, World! &amp; &lt; &gt; &quot; &#039; ">Hello, World! &amp; &lt; &gt; &quot; &#039; </div>',
            $element->__toString()
        );

        $element = new Element('div', 'Hello, World! & < > " \' ', ['class' => [
            'foo',
            'bar'
        ], 'id' => 'test', 'data-attr' => 'Hello, World! & < > " \' ', 'data-attr2' => 'Hello, World! & < > " \' ']);

        $this->assertEquals(
            '<div class="foo bar" id="test" data-attr="Hello, World! &amp; &lt; &gt; &quot; &#039; " data-attr2="Hello, World! &amp; &lt; &gt; &quot; &#039; ">Hello, World! &amp; &lt; &gt; &quot; &#039; </div>',
            $element->__toString()
        );
    }

    public function testFormatterFalse(): void
    {
        $content = 'Hello, World! & < > " \' ';
        $element = new Element('div', $content, ['class' => ['foo', 'bar'], 'id' => 'test'], false);

        $this->assertEquals(
            '<div class="foo bar" id="test">' . $content . '</div>',
            $element->__toString()
        );
        $content = 'Hello, World! & < > " \' ';
        $element = new Element('div', $content, ['class' => [
            'foo',
            'bar'
        ], 'id' => 'test', 'data-attr' => $content], false);

        $this->assertEquals(
            '<div class="foo bar" id="test" data-attr="' . $content . '">' . $content . '</div>',
            $element->__toString()
        );

        $content = 'Hello, World! & < > " \' ';

        $element = new Element('div', $content, ['class' => ['foo', 'bar'], 'id' => 'test', 'data-attr' => $content, 'data-attr2' => $content], false);

        $this->assertEquals(
            '<div class="foo bar" id="test" data-attr="' . $content . '" data-attr2="' . $content .
                '">' . $content . '</div>',
            $element->__toString()
        );
    }
}
