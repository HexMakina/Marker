<?php

declare(strict_types=1);

namespace HexMakina\Marker;

class Element
{
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
     * If not provided, the default callable $formatter uses htmlspecialchars with ENT_QUOTES
     * 
     */
    public function __construct(string $tag, string $inner = null, array $attributes = [], callable $formatter = null)
    {
        $this->tag = $tag;
        $this->inner = $inner;
        $this->formatter = $formatter ?? function ($value) {
            return htmlspecialchars($value, ENT_QUOTES);
        };

        foreach ($attributes as $name => $value) {
            // is boolean attribute ?
            if (is_int($name)) {
                $this->$value = true;
            } else {
                $this->$name = $value;
            }
        }
    }

    // magic methods to set, get, check and unset attributes
    // for boolean attribute, set to true
    public function __set(string $name, $value = null)
    {
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

        return $this->inner === null
            ? sprintf('<%s%s/>', $this->tag, $attributes)
            : sprintf('<%s%s>%s</%s>', $this->tag, $attributes, ($this->formatter)($this->inner), $this->tag);
    }
}
