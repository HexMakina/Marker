<?php

declare(strict_types=1);

use HexMakina\Marker\Form;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[CoversClass(Form::class)]
final class FormTest extends TestCase
{
    // ──────────────────────────────── input() ────────────────────────────────

    public function testInputDefaultsToTextTypeAndDerivesIdFromName(): void
    {
        $this->assertSame(
            '<input name="email" value="" type="text" id="email">',
            Form::input('email')
        );
    }

    public function testInputKeepsExplicitTypeAndBooleanAttributes(): void
    {
        // a disabled radio stays a radio: type is only defaulted when absent
        $this->assertSame(
            '<input type="radio" disabled name="opt" value="1" id="opt">',
            Form::input('opt', '1', ['type' => 'radio', 'disabled'])
        );
    }

    public function testInputAttributesArrayWinsOverArguments(): void
    {
        $this->assertSame(
            '<input name="b" value="w" type="text" id="b">',
            Form::input('a', 'v', ['name' => 'b', 'value' => 'w'])
        );
    }

    public function testInputWithEmptyOrNullNameEmitsNoId(): void
    {
        $expected = '<input name="" value="" type="text">';

        $this->assertSame($expected, Form::input(''));
        $this->assertSame($expected, Form::input());
    }

    // ─────────────────────── magic input types (__callStatic) ───────────────────────

    #[DataProvider('inputTypeProvider')]
    public function testMagicInputTypes(string $method, string $expected): void
    {
        $this->assertSame($expected, Form::$method('f', 'v'));
    }

    public static function inputTypeProvider(): iterable
    {
        yield 'hidden'   => ['hidden', '<input type="hidden" name="f" value="v" id="f">'];
        yield 'date'     => ['date', '<input type="date" name="f" value="v" id="f">'];
        yield 'checkbox' => ['checkbox', '<input type="checkbox" name="f" value="v" id="f">'];
        yield 'email'    => ['email', '<input type="email" name="f" value="v" id="f">'];
        yield 'datetime becomes datetime-local' => [
            'datetime',
            '<input type="datetime-local" name="f" value="v" id="f">',
        ];
    }

    public function testMethodNameOverridesTypeAttribute(): void
    {
        $this->assertSame(
            '<input type="hidden" name="a" value="b" id="a">',
            Form::hidden('a', 'b', ['type' => 'text'])
        );
    }

    public function testPasswordValueIsNeverEmitted(): void
    {
        $this->assertSame(
            '<input type="password" name="pwd" value="" id="pwd">',
            Form::password('pwd', 'hunter2')
        );

        // even when smuggled through the attributes array
        $this->assertSame(
            '<input value="" type="password" name="pwd" id="pwd">',
            Form::password('pwd', null, ['value' => 'leak'])
        );
    }

    // ─────────────────────────────── textarea() ───────────────────────────────

    public function testTextareaEscapesItsContent(): void
    {
        $this->assertSame(
            '<textarea name="bio" id="bio">a &amp; b</textarea>',
            Form::textarea('bio', 'a & b')
        );
    }

    public function testTextareaAcceptsNonStringValues(): void
    {
        $this->assertSame('<textarea name="n" id="n">42</textarea>', Form::textarea('n', 42));
        $this->assertSame('<textarea name="n" id="n"></textarea>', Form::textarea('n'));
    }

    // ──────────────────────────── select() & options() ────────────────────────────

    public function testSelectRendersOptionsWithSelection(): void
    {
        $this->assertSame(
            '<select name="country" id="country">'
                . '<option value="fr">France</option>'
                . '<option value="de" selected="selected">Germany</option>'
                . '</select>',
            Form::select('country', ['fr' => 'France', 'de' => 'Germany'], 'de')
        );
    }

    #[DataProvider('selectedMatchingProvider')]
    public function testSelectedMatchesOnStringForm(mixed $selected, string $selectedValue): void
    {
        // strict comparison on the string form: no loose-typing surprises,
        // while numeric-string input still matches integer array keys
        $html = Form::options(['one', 'two'], $selected);

        $this->assertStringContainsString(
            sprintf('value="%s" selected="selected"', $selectedValue),
            $html
        );
        $this->assertSame(1, substr_count($html, 'selected="selected"'));
    }

    public static function selectedMatchingProvider(): iterable
    {
        yield 'int 0 selects key 0'      => [0, '0'];
        yield 'int 1 selects key 1'      => [1, '1'];
        yield 'string "1" selects key 1' => ['1', '1'];
    }

    public function testNullSelectsNothing(): void
    {
        $this->assertStringNotContainsString('selected', Form::options(['one', 'two'], null));
    }

    public function testOptionLabelsAndValuesAreEscaped(): void
    {
        $this->assertSame(
            '<option value="k&quot;k">&lt;script&gt;x&lt;/script&gt;</option>',
            Form::options(['k"k' => '<script>x</script>'])
        );
    }

    // ─────────────────────────── security regressions ───────────────────────────

    public function testSelectAttributesAreEscapedDespiteTrustedContent(): void
    {
        // regression: the identity formatter carrying pre-rendered <option>s
        // used to leave select's *attribute* values unescaped too, letting
        // user data break out of the attribute and inject event handlers
        $this->assertSame(
            '<select title="&quot; onmouseover=&quot;alert(1)" name="s" id="s">'
                . '<option value="a">A</option></select>',
            Form::select('s', ['a' => 'A'], null, ['title' => '" onmouseover="alert(1)'])
        );
    }

    #[RequiresPhpExtension('dom')]
    public function testHostileSelectRoundTripsThroughAnHtmlParser(): void
    {
        $hostile = '"><script>alert(1)</script>';
        $html = Form::select(
            's',
            [$hostile => $hostile],
            $hostile,
            ['title' => $hostile, 'class' => [$hostile, 'x']]
        );

        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_NOERROR);
        $select = $dom->getElementsByTagName('select')->item(0);
        $option = $dom->getElementsByTagName('option')->item(0);

        $this->assertSame($hostile, $select->getAttribute('title'));
        $this->assertSame($hostile . ' x', $select->getAttribute('class'));
        $this->assertSame($hostile, $option->getAttribute('value'));
        $this->assertSame($hostile, $option->textContent);
        $this->assertSame('selected', $option->getAttribute('selected'));
        $this->assertSame(0, $dom->getElementsByTagName('script')->length);
    }

    // ──────────────────── label() / legend() / button() / submit() ────────────────────

    public function testLabelSetsForAndEscapes(): void
    {
        $this->assertSame(
            '<label for="fld">Text &amp; more</label>',
            Form::label('fld', 'Text & more')
        );
    }

    public function testLegendAndButtonEscapeTheirLabel(): void
    {
        $this->assertSame('<legend class="x">L &amp; M</legend>', Form::legend('L & M', ['class' => 'x']));
        $this->assertSame('<button type="button">Go &amp; stop</button>', Form::button('Go & stop', ['type' => 'button']));
    }

    public function testSubmitAsButtonStripsNameAndValue(): void
    {
        $this->assertSame(
            '<button type="submit" id="save">Save &amp; exit</button>',
            Form::submit('save', 'Save & exit', ['name' => 'nope', 'value' => 'nope'])
        );
    }

    public function testSubmitAsInputIsVoidAndCarriesTheLabelAsValue(): void
    {
        $this->assertSame(
            '<input type="submit" id="save" value="Save">',
            Form::submit('save', 'Save', ['tag' => 'input'])
        );

        // an explicit value wins over the label
        $this->assertSame(
            '<input value="V" type="submit" id="save">',
            Form::submit('save', 'Save', ['tag' => 'input', 'value' => 'V'])
        );
    }

    // ───────────────────────────── labelling behaviour ─────────────────────────────

    public function testLabelPseudoAttributeRendersASiblingLabel(): void
    {
        $this->assertSame(
            '<label for="email">E-mail</label><input name="email" value="" type="text" id="email">',
            Form::input('email', null, ['label' => 'E-mail'])
        );
    }

    public function testLabelWrapPseudoAttributeWrapsAndEscapes(): void
    {
        $this->assertSame(
            '<label>E-mail &amp; Co<input name="email" value="" type="text" id="email"></label>',
            Form::input('email', null, ['label' => 'E-mail & Co', 'label-wrap' => true])
        );
    }

    public function testMissingIdForcesLabelWrapping(): void
    {
        // without an id there is nothing for a sibling <label for=""> to bind to
        $this->assertSame(
            '<label>L<input name="" value="v" type="text"></label>',
            Form::input(null, 'v', ['label' => 'L'])
        );
    }

    public function testFalsyLabelWrapStaysSibling(): void
    {
        $this->assertSame(
            '<label for="x">L</label><input name="x" value="" type="text" id="x">',
            Form::input('x', null, ['label' => 'L', 'label-wrap' => 0])
        );
    }

    public function testPseudoAttributesNeverLeakIntoMarkup(): void
    {
        $html = Form::input('e', null, ['label' => 'E', 'label-wrap' => true])
            . Form::submit('s', 'S', ['tag' => 'input'])
            . Form::select('c', ['a' => 'A'], null, ['label' => 'C']);

        $this->assertStringNotContainsString('label=', $html);
        $this->assertStringNotContainsString('label-wrap', $html);
        $this->assertStringNotContainsString('tag=', $html);
    }
}
