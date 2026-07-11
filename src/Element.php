<?php

declare(strict_types=1);

namespace HexMakina\Marker;

class Element
{
    private const VOID_ELEMENTS = [
        'area', 'base', 'br', 'col', 'embed', 'hr',
        'img', 'input', 'link', 'meta', 'param',
        'source', 'track', 'wbr',
    ];

    private const NAME_PATTERN = '/^[a-zA-Z][a-zA-Z0-9-]*$/D';

    protected string $tag;
    protected ?string $inner = null;
    protected array $attributes = [];
    protected $formatter = null;

    // magic method to create an instance of Element statically
    public static function __callStatic(string $tag, array $arguments): self
    {
        return new self($tag, $arguments[0] ?? null, $arguments[1] ?? [], $arguments[2] ?? null);
    }

    /**
     * Element constructor.
     * 
     * The attributes array can contain:
     *  - boolean attributes ['checked', 'selected', 'disabled'], which are set to their name
     *  - string attributes ['id' => 'foo', 'name' => 'bar', 'class' => "foo bar"]
     *  - a mix of both ['id' => 'foo', 'checked', 'class' => "foo bar"]
     * 
     * The $formatter is applied to attribute names, values and inner content:
     *  - null or true: the default, htmlspecialchars with ENT_QUOTES
     *  - false: raw output, no formatting
     *  - callable: custom formatting
     *
     * @param string $tag HTML tag name
     * @param string $inner inner HTML content
     * @param array $attributes HTML attributes
     * @param callable|bool|null $formatter function to format attribute names, values and inner content
     */
    public function __construct(string $tag, ?string $inner = null, array $attributes = [], $formatter = null)
    {
        if (preg_match(self::NAME_PATTERN, $tag) !== 1) {
            throw new \InvalidArgumentException(sprintf('Invalid HTML tag name "%s"', $tag));
        }

        $this->tag = $tag;
        $this->inner = $inner;

        foreach ($attributes as $name => $value) {
            // is boolean attribute ?
            if (is_int($name)) {
                $this->$value = true;
            } else {
                $this->$name = $value;
            }
        }

        if ($formatter === false) {
            $formatter = fn($value) => $value;
        } elseif ($formatter === null || $formatter === true) {
            $formatter = fn($value) => htmlspecialchars($value ?? '', ENT_QUOTES);
        }

        $this->formatter = $formatter;
    }

    // magic methods to set, get, check and unset attributes
    // for boolean attribute, set to true
    public function __set(string $name, $value = null)
    {
        if (preg_match(self::NAME_PATTERN, $name) !== 1) {
            throw new \InvalidArgumentException(sprintf('Invalid HTML attribute name "%s"', $name));
        }

        $this->attributes[$name] = is_array($value) ? implode(' ', $value) : $value;
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset(string $name)
    {
        unset($this->attributes[$name]);
    }

    // magic method to render the HTML element
    public function __toString(): string
    {
        $attributes = '';

        foreach ($this->attributes as $name => $value) {
            $name = ($this->formatter)($name);
            $attributes .= ' ' . ($value === true ? $name : sprintf('%s="%s"', $name, ($this->formatter)((string)$value)));
        }

        if (in_array(strtolower($this->tag), self::VOID_ELEMENTS, true)) {
            return sprintf('<%s%s>', $this->tag, $attributes);
        }

        return sprintf(
            '<%s%s>%s</%s>',
            $this->tag,
            $attributes,
            ($this->formatter)($this->inner ?? ''),
            $this->tag
        );
    }
}
