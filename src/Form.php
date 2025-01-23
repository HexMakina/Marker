<?php

declare(strict_types=1);

namespace HexMakina\Marker;

/**
 * Provides a convenient way to generate form input elements with minimal code,
 * making it easier and faster to build forms in PHP.
 */

class Form
{
    /**
     * Used as a shortcut for creating form input elements with a single line of code
     *
     * When called on the Form class with a name that matches an HTML input element type (such as hidden, date, time, radio, datetime, etc.),
     * it intercepts the call and dynamically creates an input element of that type with the provided attributes.
     */
    public static function __callStatic(string $input_type, array $arguments): string
    {
        // arguments [name, value, [attributes]]
        $i = 0;
        $name         = $arguments[$i++] ?? null;
        $value        = $arguments[$i++] ?? null;
        $attributes   = (array)($arguments[$i++] ?? []);

        $attributes['type'] = $input_type;
        $attributes['name'] ??= $name;
        $attributes['value'] ??= $value;

        if ($attributes['type'] == 'datetime') {
            $attributes['type'] = 'datetime-local';
        } elseif ($attributes['type'] == 'password') {
            $attributes['value'] = '';
        }

        return self::input($name, $value, $attributes);
    }

    /**
     * Generates an HTML input element with the provided attributes.
     *
     * @param string|null $name The name of the input element
     * @param mixed|null $value The value of the input element
     * @param array $attributes An array containing the attributes of the input element
     * @return string The generated HTML code for the input element
     */
    public static function input(string $name = null, $value = null, array $attributes = []): string
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

        return self::labelledField('input', null, $attributes);
    }

    /**
     * Generates an HTML textarea element with the provided attributes.
     *
     * @param string $name The name of the textarea element
     * @param mixed|null $value The value of the textarea element
     * @param array $attributes An array containing the attributes of the textarea element
     * @return string The generated HTML code for the textarea element
     */
    public static function textarea(string $name, $value = null, array $attributes = []): string
    {
        $attributes['name'] ??= $name;
        return self::labelledField('textarea', $value ?? '', $attributes);
    }

    /**
     * Generates an HTML select element with the provided options and attributes.
     *
     * @param string $name The name of the select element
     * @param array $option_list An array containing the options of the select element
     * @param mixed|null $selected The selected option of the select element
     * @param array $attributes An array containing the attributes of the select element
     * @return string The generated HTML code for the select element
     */
    public static function select(string $name, array $option_list, $selected = null, array $attributes = []): string
    {
        $attributes['name'] ??= $name;
        $options = self::options($option_list, $selected);
        return self::labelledField('select', $options, $attributes);
    }

    /**
     * Generates an HTML options string for the provided option list and selected value.
     *
     * @param array $list An array containing the options of the select element
     * @param mixed|null $selected The selected option of the select element
     * @return string The generated HTML options string
     */
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

    /**
     * Generates an HTML legend element with the provided label and attributes.
     *
     * @param string $label The label of the legend element
     * @param array $attributes An array containing the attributes of the legend element
     * @return string The generated HTML code for the legend element
     */
    public static function legend(string $label, array $attributes = []): string
    {
        return '' . (new Element('legend', $label, $attributes));
    }

    /**
     * Generates an HTML label element with the provided for attribute, label text, and attributes.
     *
     * @param string $for The ID of the input element associated with the label
     * @param string $label The text of the label element
     * @param array $attributes An array containing the attributes of the label element
     * @return string The generated HTML code for the label element
     */
    public static function label(string $for, string $label, array $attributes = []): string
    {
        $attributes['for'] = $for;
        unset($attributes['label']);

        return self::labelledField('label', $label, $attributes);
    }

    public static function button(string $label, array $attributes = []): string
    {
        return  '' . (new Element('button', $label, $attributes));
    }

    /**
     * Generates a submit button element for a form.
     *
     * @param string $id The ID of the button element.
     * @param string $label The label for the button element.
     * @param array $attributes An optional array of attributes for the button element.
     *      The 'type' attribute is set to 'submit' by default and the 'name' attribute is omitted.
     *      The 'id' and 'value' attributes are automatically set based on the provided arguments.
     *      If the 'tag' attribute is set to 'input', an input element will be generated instead of a button element.
     * @return string The generated HTML for the button element.
     */
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
            $ret .= self::button($label, $attributes);
        }

        return $ret;
    }

    /**
     * Generates a labelled HTML form field, combining a form field with a label element.
     *
     * @param string $tag The HTML tag of the form field element
     * @param string|null $content The content of the form field element
     * @param array $attributes Additional attributes to add to the form field element
     * @return string The HTML for the labelled form field
     */
    private static function labelledField(string $tag, string $content = null, array $attributes = []): string
    {
        $ret = '';
        
        $attributes['id'] ??= $attributes['name'] ?? '';

        $label_text = $attributes['label'] ?? '';
        unset($attributes['label']);
        $input = new Element($tag, $content, $attributes);

        if($label_text){
            if (!empty($attributes['label-wrap']))
                $ret = self::label(null, $label_text.$input);
            else
                $ret = self::label($attributes['id'], $label_text).$input;
        }
        else
            $ret = $input;

        return $ret;
    }
}
