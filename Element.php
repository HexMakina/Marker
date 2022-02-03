<?php

namespace HexMakina\Marker;

class Element
{
    const VOID_ELEMENTS = [
      'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
      'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    protected $tag = '';
    protected $attributes = [];
    protected $content = '';

    public function isVoid()
    {
        return in_array($this->tag, self::VOID_ELEMENTS);
    }

    public function __construct($tag, $content = null, $attributes = [])
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
        $this->content = $content ?? '';
    }

    private function formatAttributes()
    {
        $ret = '';

        foreach ($this->attributes as $k => $v) {
            if ($this->isValidValue($v)) {
                $ret .
                =  ' '.($this->isBooleanAttribute($k) ? $v : sprintf('%s="%s"', $k, $v));
            }
        }
        return $ret;
    }

    private function isBooleanAttribute($k){
      return is_int($k);
    }

    private function isValidValue($v){
      return !(is_null($v) && $v === '' && is_array($v));
    }

    public function __toString()
    {
        $flattributes = $this->formatAttributes();

        $ret = '';
        if ($this->isVoid()) {
            $ret = sprintf('<%s%s/>', $this->tag, $flattributes);
        } else {
            $ret = sprintf(
                '<%s%s>%s</%s>',
                $this->tag,
                $flattributes,
                $this->content,
                $this->tag
            );
        }

        return $ret;
    }
}
