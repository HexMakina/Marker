<?php

declare(strict_types=1);

use HexMakina\Marker\Element;
use HexMakina\Marker\Marker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Marker::class)]
final class MarkerTest extends TestCase
{
    public function testImgMirrorsAltAsTitle(): void
    {
        $this->assertSame(
            '<img src="cat.jpg" alt="A cat" title="A cat">',
            (string) Marker::img('cat.jpg', 'A cat')
        );
    }

    public function testImgExplicitAttributesWin(): void
    {
        $this->assertSame(
            '<img title="Override" src="cat.jpg" alt="A cat">',
            (string) Marker::img('cat.jpg', 'A cat', ['title' => 'Override'])
        );
    }

    public function testImgEscapesAlt(): void
    {
        $this->assertSame(
            '<img src="c.jpg" alt="Tom &amp; Jerry" title="Tom &amp; Jerry">',
            (string) Marker::img('c.jpg', 'Tom & Jerry')
        );
    }

    public function testAnchorForcesHrefAndEscapesLabel(): void
    {
        $this->assertSame(
            '<a class="nav" href="/path">Click &amp; go</a>',
            (string) Marker::a('/path', 'Click & go', ['class' => 'nav'])
        );

        // the href argument overwrites any href attribute
        $this->assertSame(
            '<a href="/real">x</a>',
            (string) Marker::a('/real', 'x', ['href' => '/fake'])
        );
    }

    public function testInheritsTheElementFactoryForEveryOtherTag(): void
    {
        // __callStatic uses `new self`: the factory builds Element instances
        // even when invoked through the Marker subclass
        $this->assertInstanceOf(Element::class, Marker::div('x'));
        $this->assertSame('<div>x</div>', (string) Marker::div('x'));
    }
}
