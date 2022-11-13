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

    public static function __callStatic($element_type, $arguments): string
    {
      // arguments [name, value, [attributes], [errors]]
        $i = 0;
        $field_name =  $arguments[$i++] ?? null;
        $field_value = $arguments[$i++] ?? null;
        $attributes =  $arguments[$i++] ?? [];
        $errors =      $arguments[$i++] ?? [];


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

    public static function input($field_name, $field_value = null, $attributes = [], $errors = []): string
    {
        if (!isset($attributes['type']) || isset($attributes['disabled']) || in_array('disabled', $attributes, true)) {
            $attributes['type'] = 'text';
        }

        $attributes['name'] = $attributes['name'] ?? $field_name;
        $attributes['value'] = $attributes['value'] ?? $field_value;

        return self::elementWithErrors('input', null, $attributes, isset($errors[$field_name]));
    }

    public static function textarea($field_name, $field_value = null, $attributes = [], $errors = []): string
    {
        $attributes['name'] = $attributes['name'] ?? $field_name;
        return self::elementWithErrors('textarea', $field_value, $attributes, isset($errors[$field_name]));
    }

    public static function select($field_name, $option_list, $selected = null, $attributes = [], $errors = []): string
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

    public static function legend($label, $attributes = []): string
    {
        return '' . (new Element('legend', $label, $attributes));
    }

    public static function label($field_for, $field_label, $attributes = [], $errors = []): string
    {
        $attributes['for'] = $field_for;
        return self::elementWithErrors('label', $field_label, $attributes, isset($errors[$field_for]));
    }

    public static function submit($field_id, $field_label, $attributes = []): string
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

    private static function elementWithErrors($tag, $content, $attributes = [], $errors = false): string
    {

        $attributes['id'] = $attributes['id'] ?? $attributes['name'] ?? '';

        if ($errors === true) {
            $attributes['class'] = $attributes['class'] ?? '';
            $attributes['class'] .= ' error';
        }

        $label = '';
        if (isset($attributes['label'])) {
            $label = self::label($attributes['id'], $attributes['label'], [], $errors);
            unset($attributes['label']);
        }

      // vd($attributes, $tag);
        return $label . (new Element($tag, $content, $attributes));
    }
}
