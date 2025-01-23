<?php

declare(strict_types=1);

namespace HexMakina\Marker;

class Element
{
    protected string $tag;
    protected ?string $inner;
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
     * If not provided, the default $formatter uses htmlspecialchars with ENT_QUOTES
     * 
     * @param string $tag HTML tag name
     * @param string $inner inner HTML content
     * @param array $attributes HTML attributes
     * @param callable|bool $formatter function to format attribute values and inner content
     */
    public function __construct(string $tag, string $inner = null, array $attributes = [], $formatter = null)
    {
        $this->tag = $tag;
        $this->inner = $inner;
        foreach($attributes as $name => $value)
        {
            // is boolean attribute ?
            if(is_int($name))
            {
                $this->$value = true;
            }
            else
            {
                $this->$name = $value;
            }
        }

        $this->formatter = $formatter === false ? fn($v) => $v : $formatter ?? fn($value) => htmlspecialchars($value ?? '', ENT_QUOTES);
    }

    // magic methods to set, get, check and unset attributes
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

    // magic method to render the HTML element
    public function __toString(): string
    {
        $attributes = '';
        
        foreach ($this->attributes as $name => $value) {
            $name = call_user_func($this->formatter, $name);
            $attributes .= ' ' . ($value === true ? $name : sprintf('%s="%s"', $name, call_user_func($this->formatter, (string)$value)));
        }

        return $this->inner === null
            ? sprintf('<%s%s/>', $this->tag, $attributes)
            : sprintf('<%s%s>%s</%s>', $this->tag, $attributes, call_user_func($this->formatter, $this->inner), $this->tag);
    }
}
