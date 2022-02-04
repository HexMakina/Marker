<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \HexMakina\Marker\Element;

final class ElementTest extends TestCase
{
  public function testEmptyConstructor(): void
  {
      $this->expectException(ArgumentCountError::class);
      $e = new Element();
  }

  public function testCreateEmptyElement(): void
  {
    $e = new Element('p');
    $this->assertEquals('<p></p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag);
      $this->assertEquals('<'.$tag.'/>', $e->__toString());
    }
  }


  public function testCreateElementWithContent(): void
  {
    $message = null;
    $e = new Element('p', $message);
    $this->assertEquals('<p></p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message);
      $this->assertEquals('<'.$tag.'/>', $e->__toString());
    }

    $message = 'lorem ipsum';
    $e = new Element('p', $message);
    $this->assertEquals('<p>'.$message.'</p>', $e->__toString());

    $e = new Element('br', $message);
    $this->assertEquals('<br/>', $e->__toString());
  }

  public function testCreateElementWithAttributes(): void
  {
    $message = null;
    $attributes = ['id' => 'test_id'];
    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p id="test_id"></p>', $e->__toString());

    $e = new Element('br', $message,  $attributes);
    $this->assertEquals('<br id="test_id"/>', $e->__toString());


    $message = 'lorem ipsum';
    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p id="test_id">'.$message.'</p>', $e->__toString());

    $e = new Element('br', $message,  $attributes);
    $this->assertEquals('<br id="test_id"/>', $e->__toString());
  }

  public function testAttributesOrdering(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id', 'class' => 'test_class', 'style="color:red;"'];
    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p id="test_id" class="test_class" style="color:red;">'.$message.'</p>', $e->__toString());
  }
}
