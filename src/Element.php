<?php
declare(strict_types=1);

namespace HexMakina\Marker;

class Element
{
    /**
     * @var string[]
     */
    public const VOID_ELEMENTS = [
      'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
      'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    /**
     * @var string
     */
    public const FORMAT_VOID = '<%s%s/>';

    /**
     * @var string
     */
    public const FORMAT_ELEMENT = '<%s%s>%s</%s>';

    /**
     * @var string
     */
    public const FORMAT_ATTRIBUTES = '%s="%s"';


    protected string $tag;

    protected array $attributes;

    protected string $content;


    /**
     * ::span('inner text', ['class' => 'd-block'])
     * ::p('lorem ipsum')
     * ::img('alternative text', ['src' => 'path/to/img', 'width' => 100, 'height'=>100])
     * ::a('click here', ['href' => 'url/to/destination', 'class' => 'nav-link'])
     * ::a('anchor title', ['name' => 'anchor_name'])
     *
     * @param mixed[] $arguments
     */
    public static function __callStatic(string $tag, array $arguments): Element
    {
        // first argument is the inner text
        $inner_text = $arguments[0] ?? null;
        // second argument, an array for HTML attributes
        $attributes = $arguments[1] ?? [];

        return new Element($tag, $inner_text, $attributes);
    }

    /**
     * @param mixed[] $attributes
     */
    public function __construct(string $tag, string $content = null, array $attributes = [])
    {
        $this->tag = $tag;
        $this->content = $content ?? '';
        $this->attributes = $attributes;
    }

    public function __toString(): string
    {
        $attributes = self::attributesAsString($this->attributes);

        if ($this->isVoid()) {
            return sprintf(
                self::FORMAT_VOID,
                $this->tag,
                $attributes,
            );
        }

        return sprintf(
            self::FORMAT_ELEMENT,
            $this->tag,
            $attributes,
            $this->content,
            $this->tag
        );
    }

    public function isVoid(): bool
    {
        return in_array($this->tag, self::VOID_ELEMENTS);
    }


    private static function isBooleanAttribute($k): bool
    {
        return is_int($k);
    }

    private static function isValidValue($v): bool
    {
        return !is_null($v) && $v != '' && !is_array($v);
    }

    public static function attributesAsString(array $attributes = []): string
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
