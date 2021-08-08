<?php

namespace HexMakina\Marker;

class Element
{
  const VOID_ELEMENTS = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

  protected $tagName = '';
  protected $attributeList = [];
  protected $classList = [];
  protected $innerContent = '';

  public function is_void()
  {
    return in_array($this->tagName, self::VOID_ELEMENTS);
  }

  public function __construct($tagName, $innerContent=null, $attributeList = [])
  {
    $this->tagName = $tagName;
    $this->attributeList = $attributeList;
    $this->innerContent = $innerContent ?? '';
  }

  private function format_attributes()
  {
    $ret = '';

    foreach($this->attributeList as $k => $v)
      if(!is_null($v) && $v !== '' && !is_array($v))
        $ret .=  is_int($k) ? " $v" : sprintf(' %s="%s"', $k, $v);

    return $ret;
  }

  public function __toString()
  {
    $flattributes = $this->format_attributes();

    $ret = '';
    if($this->is_void())
      $ret = sprintf('<%s%s/>', $this->tagName, $flattributes);
    else
    {
      $ret = sprintf('<%s%s>%s</%s>',
        $this->tagName,
        $flattributes,
        $this->innerContent,
        $this->tagName);
    }

    return $ret;
  }
}
