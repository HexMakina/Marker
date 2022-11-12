<?php

namespace HexMakina\Marker;

class Element
{
    public const VOID_ELEMENTS = [
      'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
      'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    public const FORMAT_VOID = '<%s%s/>';
    public const FORMAT_ELEMENT = '<%s%s>%s</%s>';
    public const FORMAT_ATTRIBUTES = '%s="%s"';


    protected string $tag;
    protected array $attributes;
    protected string $content;

    public function __construct($tag, string $content = null, array $attributes = [])
    {
        $this->tag = $tag;
        $this->content = $content ?? '';
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $ret = '';
        $attributes = self::attributesAsString($this->attributes);

        if ($this->isVoid()) {
            $ret = sprintf(
                self::FORMAT_VOID,
                $this->tag,
                $attributes,
            );
        } else {
            $ret = sprintf(
                self::FORMAT_ELEMENT,
                $this->tag,
                $attributes,
                $this->content,
                $this->tag
            );
        }
        return $ret;
    }

    public function isVoid()
    {
        return in_array($this->tag, self::VOID_ELEMENTS);
    }


    private static function isBooleanAttribute($k)
    {
        return is_int($k);
    }

    private static function isValidValue($v)
    {
        return !is_null($v) && $v != '' && !is_array($v);
    }

    public static function attributesAsString($attributes = [])
    {
        $ret = '';
        foreach ($attributes as $k => $v) {
            if (self::isValidValue($v)) {
                $ret .=  ' ' . (self::isBooleanAttribute($k) ? $v : sprintf(self::FORMAT_ATTRIBUTES, $k, $v));
            }
        }
        return $ret;
    }
}
