<?php

namespace HexMakina\Marker;

/**
*
*/
class Form
{

    public static function __callStatic($element_type, $arguments)
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

    public static function input($field_name, $field_value = null, $attributes = [], $errors = [])
    {

        if (isset($attributes['disabled']) || !isset($attributes['type']) || in_array('disabled', $attributes, true)) {
            $attributes['type'] = 'text';
        } else {
            $attributes['type'] = $attributes['type'] ?? 'text';
        }

        $attributes['name'] = $attributes['name'] ?? $field_name;
        $attributes['value'] = $attributes['value'] ?? $field_value;

        return self::elementWithErrors('input', null, $attributes, isset($errors[$field_name]));
    }

    public static function textarea($field_name, $field_value = null, $attributes = [], $errors = [])
    {
        $attributes['name'] = $attributes['name'] ?? $field_name;
        return self::elementWithErrors('textarea', $field_value, $attributes, isset($errors[$field_name]));
    }

    public static function select($field_name, $option_list, $selected = null, $attributes = [], $errors = [])
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

    public static function legend($label, $attributes = [])
    {
        return new Element('legend', $label, $attributes);
    }

    public static function label($field_for, $field_label, $attributes = [], $errors = [])
    {
        $attributes['for'] = $field_for;
        return self::elementWithErrors('label', $field_label, $attributes, isset($errors[$field_for]));
    }

    public static function submit($field_id, $field_label, $attributes = [])
    {
        $attributes['id'] = $attributes['id'] ?? $field_id;
        unset($attributes['name']);
        $attributes['type'] = 'submit';
        if (isset($attributes['tag']) && $attributes['tag'] === 'input') {
            return new Element('input', '', $attributes);
        } else {
            unset($attributes['tag']);
            unset($attributes['value']);
            return new Element('button', $field_label, $attributes);
        }
    }

    private static function elementWithErrors($tag, $content, $attributes = [], $errors = false)
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
