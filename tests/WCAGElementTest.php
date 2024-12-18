<?php

use PHPUnit\Framework\TestCase;
use HexMakina\Marker\WCAGElement;

class WCAGElementTest extends TestCase
{
    public function testImgWithAlt()
    {
        $src = 'image.jpg';
        $alt = 'An image';
        $attributes = ['class' => 'img-class'];

        $result = WCAGElement::img($src, $alt, $attributes);

        $this->assertStringStartsWith('<img', $result);
        $this->assertStringContainsString('src="image.jpg"', $result);
        $this->assertStringContainsString('alt="An image"', $result);
        $this->assertStringContainsString('class="img-class"', $result);
        $this->assertStringEndsWith('/>', $result);
    }

    public function testImgWithEmptyAlt()
    {
        $expected = '<img src="image.jpg" alt=""/>';

        $img = WCAGElement::img('image.jpg');
        $this->assertEquals($expected, (string)$img);

        $img = WCAGElement::img('image.jpg', '');
        $this->assertEquals($expected, (string)$img);

        $img = WCAGElement::img('image.jpg', false);
        $this->assertEquals($expected, (string)$img);

        $img = WCAGElement::img('image.jpg', 0);
        $this->assertEquals('<img src="image.jpg" alt="0"/>', (string)$img);
    }

    public function testFigureWithCaption()
    {
        $content = '<img src="image.jpg" alt="An image">';
        $caption = 'This is a caption';
        $attributes = ['class' => 'figure-class'];

        $result = (string)WCAGElement::figure($content, $caption, $attributes);

        $this->assertStringContainsString('<figcaption>This is a caption</figcaption>', $result);
        $this->assertStringContainsString('class="figure-class"', $result);
    }

    public function testFigureWithoutCaption()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The <figcaption> is required inside <figure>.");

        WCAGElement::figure('<img src="image.jpg" alt="An image">', '');
    }

    public function testButtonWithContent()
    {
        $content = 'Click me';
        $attributes = ['class' => 'btn-class'];

        $result = WCAGElement::button($content, $attributes);

        $this->assertStringContainsString('Click me', $result);
        $this->assertStringContainsString('class="btn-class"', $result);
        $this->assertStringContainsString('role="button"', $result);
    }

    public function testButtonWithoutContent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Button content is required.");

        WCAGElement::button('');
    }

    public function testButtonWithRole()
    {
        $result = WCAGElement::button('Click me', ['role' => 'submit']);
        $this->assertStringContainsString('role="submit"', $result);
    }

    public function testInput()
    {
        $result = WCAGElement::input('text', 'name', ['class' => 'input-class', 'value' => '1']);
        $this->assertStringStartsWith('<input', $result);
        $this->assertStringContainsString('type="text"', $result);
        $this->assertStringContainsString('name="name"', $result);
        $this->assertStringContainsString('value="1"', $result);
        $this->assertStringContainsString('class="input-class"', $result);
        $this->assertStringEndsWith('/>', $result);
    }

    public function testInputWithoutType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Input type is required");

        WCAGElement::input('', 'name');
    }

    public function testInputWithoutName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Input name is required");

        WCAGElement::input('text', '');
    }

    public function testInputWithRequired()
    {
        $result = WCAGElement::input('text', 'name', ['required' => 'required']);
        $this->assertStringContainsString('required', $result);
        $this->assertStringContainsString('aria-required="true"', $result);

        $result = WCAGElement::input('text', 'name', ['required']);
        $this->assertStringContainsString('required', $result);
        $this->assertStringContainsString('aria-required="true"', $result);

        $result = WCAGElement::input('text', 'name', ['required' => '']);
        $this->assertStringContainsString('required', $result);
        $this->assertStringNotContainsString('aria-required="true"', $result);
    }

    public function testAWithHref()
    {
        $content = 'Click me';
        $href = 'https://example.com';
        $attributes = ['class' => 'link-class'];

        $result = WCAGElement::a($href, $content, $attributes);
        $this->assertStringStartsWith('<a', $result);
        $this->assertStringContainsString('href="https://example.com"', $result);
        $this->assertStringContainsString('class="link-class"', $result);
        $this->assertStringContainsString('Click me', $result);
        $this->assertStringEndsWith('</a>', $result);
    }

    public function testAWithoutHref()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Anchor href is required");

        WCAGElement::a('', 'Click me');
    }

    public function testArea()
    {
        $result = WCAGElement::area('An area', ['shape' => 'rect', 'coords' => '34,44,270,350']);
        $this->assertStringStartsWith('<area', $result);
        $this->assertStringContainsString('alt="An area"', $result);
        $this->assertStringContainsString('shape="rect"', $result);
        $this->assertStringContainsString('coords="34,44,270,350"', $result);
        $this->assertStringEndsWith('/>', $result);
    }

    public function testAreaWithoutAlt()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Area alt is required");

        WCAGElement::area('', ['shape' => 'rect', 'coords' => '34,44,270,350']);
    }

    public function testSelect()
    {
        $result = WCAGElement::select('name', [], ['class' => 'select-class']);
        $this->assertStringStartsWith('<select', $result);
        $this->assertStringContainsString('name="name"', $result);
        $this->assertStringContainsString('class="select-class"', $result);
        $this->assertStringEndsWith('</select>', $result);
    }

    public function testSelectWithoutName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Select name is required");

        WCAGElement::select('', []);
    }

    public function testIframe()
    {
        $result = (string)WCAGElement::iframe('https://example.com', 'title-test', ['class' => 'iframe-class']);
        $this->assertStringStartsWith('<iframe', $result);
        $this->assertStringContainsString('src="https://example.com"', $result);
        $this->assertStringContainsString('class="iframe-class"', $result);
        $this->assertStringContainsString('title="title-test"', $result);
        $this->assertStringEndsWith('/>', $result);
    }

}