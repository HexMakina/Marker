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
    $message = 'lorem ipsum';
    $e = new Element('p', $message);
    $this->assertEquals('<p>'.$message.'</p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message);
      $this->assertEquals('<'.$tag.'/>', $e->__toString());
    }


    $message = null;
    $e = new Element('p', $message);
    $this->assertEquals('<p></p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message);
      $this->assertEquals('<'.$tag.'/>', $e->__toString());
    }

    $message = [];
    $e = new Element('p', $message);
    $this->assertEquals('<p></p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message);
      $this->assertEquals('<'.$tag.'/>', $e->__toString());
    }
  }

  public function testCreateElementWithAttributes(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id'];

    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p id="test_id">'.$message.'</p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message,  $attributes);
      $this->assertEquals('<'.$tag.' id="test_id"/>', $e->__toString());
    }
  }

  public function testCreateElementWithBooleanAttributes(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id', 'checked', 'class' => 'test_class', 'required'];

    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p id="test_id" checked class="test_class" required>'.$message.'</p>', $e->__toString());

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message,  $attributes);
      $this->assertEquals('<'.$tag.' id="test_id" checked class="test_class" required/>', $e->__toString());
    }
  }

  public function testAttributesOrdering(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id', 'class' => 'test_class', 'style="color:red;"'];
    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p id="test_id" class="test_class" style="color:red;">'.$message.'</p>', $e->__toString());

    $attributes = ['class' => 'test_class', 'style="color:red;"','id' => 'test_id'];
    $e = new Element('p', $message,  $attributes);
    $this->assertEquals('<p class="test_class" style="color:red;" id="test_id">'.$message.'</p>', $e->__toString());
  }

  public function testCallStatic(): void
  {
    $message = 'lorem ipsum';
    $attributes = ['id' => 'test_id', 'checked', 'class' => 'test_class', 'required'];

    $e = new Element('p', $message,  $attributes);
    $e_stat = Element::p($message,  $attributes);
    $this->assertEquals($e->__toString(), $e_stat);

    foreach(Element::VOID_ELEMENTS as $tag){
      $e = new Element($tag, $message,  $attributes);
      $e_stat = Element::$tag($message,  $attributes);
      $this->assertEquals($e_stat, $e->__toString());
    }
  }

}
