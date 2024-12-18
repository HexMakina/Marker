<?php

declare(strict_types=1);

namespace HexMakina\Marker;

class Element
{
    protected string $tag;
    protected ?string $inner;
    protected array $attributes = [];
    protected $formatter;

    public const FORMAT_TAG_VOID = '<%s%s/>';
    public const FORMAT_TAG = '<%s%s>%s</%s>';
    public const FORMAT_ATTRIBUTE = ' %s="%s"';

    public static function __callStatic(string $tag, array $arguments): self
    {
        return new self($tag, $arguments[0] ?? null, $arguments[1] ?? [], $arguments[2] ?? null);
    }

    public function __construct(string $tag, string $inner = null, array $attributes = [], callable $formatter = null)
    {
        $this->tag = $tag;
        $this->inner = $inner;
        $this->formatter = $formatter ?? function($value){
            return htmlspecialchars($value, ENT_QUOTES);
        };

        foreach ($attributes as $name => $value) {
            if (is_int($name)) {
                $name = $value;
            }
            $this->$name = $value;
        }
    }

    public function __set(string $name, $value = null)
    {
        $this->attributes[$name] = is_array($value) ? implode(' ', $value) : $value;
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? '';
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset(string $name)
    {
        unset($this->attributes[$name]);
    }

    public function __toString(): string
    {
        $attributes = '';
        foreach ($this->attributes as $name => $value) {
            $value = call_user_func($this->formatter, $value);
            $attributes .= sprintf(self::FORMAT_ATTRIBUTE, $name, $value);
        }

        return $this->inner === null
            ? sprintf(self::FORMAT_TAG_VOID, $this->tag, $attributes)
            : sprintf(self::FORMAT_TAG, $this->tag, $attributes, call_user_func($this->formatter, $this->inner), $this->tag);
    }
}
