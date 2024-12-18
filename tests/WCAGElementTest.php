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

    public function testImgWithoutAlt()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The 'alt' attribute is required for <img>.");

        WCAGElement::img('image.jpg', '');
    }

    public function testFigureWithCaption()
    {
        $content = '<img src="image.jpg" alt="An image">';
        $caption = 'This is a caption';
        $attributes = ['class' => 'figure-class'];

        $result = WCAGElement::figure($content, $caption, $attributes);

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
    }

    public function testButtonWithoutContent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Button content is required.");

        WCAGElement::button('');
    }
}