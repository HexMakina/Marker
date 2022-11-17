<?php

declare(strict_types=1);

namespace HexMakina\Marker;

/**
  * @method static string hidden(string $name, $value=null)
  * @method static string date()
  * @method static string time()
  * @method static string datetime()
  */

class Form
{
    public static function __callStatic(string $element_type, array $arguments): string
    {
      // arguments [name, value, [attributes], [errors]]
        $i = 0;
        $name         = $arguments[$i++] ?? null;
        $value        = $arguments[$i++] ?? null;
        $attributes   = (array)($arguments[$i++] ?? []);
        $errors       = (array)($arguments[$i++] ?? []);

        $attributes['type'] = $element_type;
        $attributes['name'] ??= $name;
        $attributes['value'] ??= $value;

        if ($attributes['type'] == 'datetime') {
            $attributes['type'] = 'datetime-local';
        } elseif ($attributes['type'] == 'password') {
            $attributes['value'] = '';
        }

        return self::input($name, $value, $attributes, $errors);
    }

    public static function input(string $name = null, $value = null, array $attributes = [], array $errors = []): string
    {
        $attributes['name'] ??= $name;
        $attributes['value'] ??= $value;

        if (
              !isset($attributes['type'])
            || isset($attributes['disabled'])
            || in_array('disabled', $attributes, true)
        ) {
            // why are disabled field textual ?
            // radio or checkbox can be disabled too..
            $attributes['type'] = 'text';
        }

        return self::elementWithErrors('input', null, $attributes, isset($errors[$name]));
    }

    public static function textarea(string $name, $value = null, array $attributes = [], array $errors = []): string
    {
        $attributes['name'] ??= $name;
        return self::elementWithErrors('textarea', $value, $attributes, isset($errors[$name]));
    }

    public static function select(string $name, array $option_list, $selected = null, array $attributes = [], array $errors = []): string
    {
        $attributes['name'] ??= $name;
        $options = self::options($option_list, $selected);
        return self::elementWithErrors('select', $options, $attributes, isset($errors[$name]));
    }

    public static function options(array $list, $selected = null): string
    {
        $options = '';
        foreach ($list as $value => $label) {
            $option_attributes = ['value' => $value];
            if ($selected == $value) {
                $option_attributes['selected'] =  'selected';
            }

            $options .= new Element('option', "$label", $option_attributes);
        }

        return $options;
    }

    public static function legend(string $label, array $attributes = []): string
    {
        return '' . (new Element('legend', $label, $attributes));
    }

    public static function label(string $for, string $label, array $attributes = [], array $errors = []): string
    {
        $attributes['for'] = $for;
        unset($attributes['label']);

        return self::elementWithErrors('label', $label, $attributes, isset($errors[$for]));
    }


    public static function submit(string $id, string $label, array $attributes = []): string
    {
        $ret = '';

        $attributes['type'] = 'submit';
        unset($attributes['name']);

        $attributes['id'] ??= $id;
        $attributes['value'] ??= $label;

        if (isset($attributes['tag']) && $attributes['tag'] === 'input') {
            unset($attributes['tag']);
            $ret .= new Element('input', '', $attributes);
        } else {
            unset($attributes['tag']);
            unset($attributes['value']);
            $ret .= new Element('button', $label, $attributes);
        }

        return $ret;
    }

    private static function elementWithErrors(string $tag, string $content = null, array $attributes = [], bool $hasErrors = false): string
    {
        $attributes['id'] ??= $attributes['name'] ?? '';

        if ($hasErrors) {
            $attributes['class'] ??= '';
            $attributes['class'] .= ' error';
        }

        $label = '';
        if (isset($attributes['label'])) {
            $label = self::label($attributes['id'], $attributes['label'], [], ['' . $attributes['id'] => 'error']);
            unset($attributes['label']);
        }

        return $label . (new Element($tag, $content, $attributes));
    }
}
