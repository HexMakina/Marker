<?php

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
        $field_name =  $arguments[$i++] ?? null;
        $field_value = $arguments[$i++] ?? null;
        $attributes =  (array)($arguments[$i++] ?? []);
        $errors =      (array)($arguments[$i++] ?? []);

        $attributes['type'] = $element_type;
        $attributes['name'] = $attributes['name'] ?? $field_name;
        $attributes['value'] = $attributes['value'] ?? $field_value;

        switch ($attributes['type']) {
            case 'datetime':
                $attributes['type'] = 'datetime-local';
                break;

            case 'password':
                $attributes['value'] = '';
                break;
        }

        return self::input($field_name, $field_value, $attributes, $errors);
    }


    public static function input(string $field_name, mixed $field_value = null, array $attributes = [], array $errors = []): string
    {
        if (!isset($attributes['type']) || isset($attributes['disabled']) || in_array('disabled', $attributes, true)) {
            $attributes['type'] = 'text';
        }

        $attributes['name'] = $attributes['name'] ?? $field_name;
        $attributes['value'] = $attributes['value'] ?? $field_value;

        return self::elementWithErrors('input', null, $attributes, isset($errors[$field_name]));
    }


    public static function textarea(string $field_name, mixed $field_value = null, array $attributes = [], array $errors = []): string
    {
        $attributes['name'] = $attributes['name'] ?? $field_name;
        return self::elementWithErrors('textarea', $field_value, $attributes, isset($errors[$field_name]));
    }


    public static function select(string $field_name, array $option_list, mixed $selected = null, array $attributes = [], array $errors = []): string
    {
        $attributes['name'] = $attributes['name'] ?? $field_name;

        $options = '';
        foreach ($option_list as $value => $label) {
            $option_attributes = ['value' => $value];
            if ($selected == $value) {
                $option_attributes['selected'] =  'selected';
            }

            $options .= new Element('option', $label, $option_attributes);
        }
        return self::elementWithErrors('select', $options, $attributes, isset($errors[$field_name]));
    }

    public static function legend(string $label, array $attributes = []): string
    {
        return '' . (new Element('legend', $label, $attributes));
    }

    public static function label(string $field_for, string $field_label, array $attributes = [], array $errors = []): string
    {
        $attributes['for'] = $field_for;
        unset($attributes['label']);

        return self::elementWithErrors('label', $field_label, $attributes, isset($errors[$field_for]));
    }


    public static function submit(string $field_id, string $field_label, array $attributes = []): string
    {
        $ret = '';

        $attributes['type'] = 'submit';
        unset($attributes['name']);

        $attributes['id'] = $attributes['id'] ?? $field_id;
        $attributes['value'] = $attributes['value'] ?? $field_label;

        if (isset($attributes['tag']) && $attributes['tag'] === 'input') {
            unset($attributes['tag']);
            $ret .= new Element('input', '', $attributes);
        } else {
            unset($attributes['tag']);
            unset($attributes['value']);
            $ret .= new Element('button', $field_label, $attributes);
        }

        return $ret;
    }


    private static function elementWithErrors(string $tag, string $content = null, array $attributes = [], bool $hasErrors = false): string
    {
        $attributes['id'] = $attributes['id'] ?? $attributes['name'] ?? '';

        if ($hasErrors === true) {
            $attributes['class'] = $attributes['class'] ?? '';
            $attributes['class'] .= ' error';
        }

        $label = '';
        if (isset($attributes['label'])) {
            $label = self::label($attributes['id'], $attributes['label'], [], [''.$attributes['id'] => 'error']);
            unset($attributes['label']);
        }

        return $label . (new Element($tag, $content, $attributes));
    }
}
