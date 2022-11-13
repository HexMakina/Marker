<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \HexMakina\Marker\Element;

final class ElementTest extends TestCase
{
  private $tags;
  private $tags_void;

  public function setUp(): void
  {
    // https://html.spec.whatwg.org/multipage/indices.html#elements-3
    $this->tags = ['a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 'b', 'base', 'bdi', 'bdo', 'blockquote', 'body', 'br', 'button', 'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 'data', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hgroup', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'menu', 'meta', 'meter', 'nav', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'picture', 'pre', 'progress', 'q', 'rp', 'rt', 'ruby', 's', 'samp', 'script', 'section', 'select', 'slot', 'small', 'source', 'span', 'strong', 'style', 'sub', 'summary', 'sup', 'SVG svg', 'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track', 'u', 'ul', 'var', 'video', 'wbr'];

  }

  public function testEmptyConstructor(): void
  {
      $this->expectException(ArgumentCountError::class);
      new Element();
  }

  public function testCreateEmptyElement(): void
  {
    foreach($this->tags as $tag)
    {
      $e = new Element($tag);
      if(in_array($tag, Element::VOID_ELEMENTS)){
        $this->assertEquals('<'.$tag.'/>', $e->__toString());
      }
      else {
        $this->assertEquals("<$tag></$tag>", $e->__toString());
      }
    }
  }


  public function testCreateElementWithContent(): void
  {
    $messages = ['lorem ipsum' => 'lorem ipsum', '' => '', null => ''];
    foreach($messages as $message => $expected){
      foreach($this->tags as $tag)
      {
        $e = new Element($tag, $message);

        if(in_array($tag, Element::VOID_ELEMENTS)){
          $this->assertEquals('<'.$tag.'/>', $e->__toString());
        }
        else {
          $this->assertEquals("<$tag>$expected</$tag>", $e->__toString());
        }
      }
    }
  }

  // Attributes
  public function testCreateElementWithAttributes(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id'];
    $attributes_expected_string = ' id="test_id"';

    // testing attributes string generator
    $this->assertEquals(Element::attributesAsString($attributes), $attributes_expected_string);

    // testing all HTML tags
    $this->assertForAllHTMLTags($message, $attributes, $attributes_expected_string);
  }

  public function testCreateElementWithBooleanAttributes(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id', 'checked', 'class' => 'test_class', 'required'];
    $attributes_expected_string = ' id="test_id" checked class="test_class" required';

    // testing attributes string generator
    $this->assertEquals(Element::attributesAsString($attributes), $attributes_expected_string);

    // testing all HTML tags
    $this->assertForAllHTMLTags($message, $attributes, $attributes_expected_string);
  }

  public function testAttributesOrdering(): void
  {
    $message = 'lorem ipsum';

    $attributes = ['id' => 'test_id', 'class' => 'test_class', 'style="color:red;"'];
    $attributes_expected_string = ' id="test_id" class="test_class" style="color:red;"';

    // testing attributes string generator
    $this->assertEquals(Element::attributesAsString($attributes), $attributes_expected_string);

    // testing all HTML tags
    $this->assertForAllHTMLTags($message, $attributes, $attributes_expected_string);


    $attributes = ['class' => 'test_class', 'style="color:red;"','id' => 'test_id'];
    $attributes_expected_string = ' class="test_class" style="color:red;" id="test_id"';

    // testing attributes string generator
    $this->assertEquals(Element::attributesAsString($attributes), $attributes_expected_string);

    // testing all HTML tags
    $this->assertForAllHTMLTags($message, $attributes, $attributes_expected_string);
  }


  private function assertForAllHTMLTags($message, $attributes, $attributes_expected_string)
  {
    foreach($this->tags as $tag){

      // testing by instantiation
      $e = new Element($tag, $message,  $attributes);
      if(in_array($tag, Element::VOID_ELEMENTS)){
        $this->assertEquals(sprintf(Element::FORMAT_VOID, $tag, $attributes_expected_string), $e->__toString());
      }
      else {
        $this->assertEquals(sprintf(Element::FORMAT_ELEMENT, $tag, $attributes_expected_string, $message, $tag), $e->__toString());
      }
    }
  }
}
