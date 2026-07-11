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

        if ($attributes['type'] === 'datetime') {
            $attributes['type'] = 'datetime-local';
        } elseif ($attributes['type'] === 'password') {
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
    public static function input(?string $name = null, $value = null, array $attributes = []): string
    {
        $attributes['name'] ??= $name;
        $attributes['value'] ??= $value;

        // default to a text input only when no type was given;
        // a disabled field keeps whatever type it was given (radio, checkbox, ...)
        $attributes['type'] ??= 'text';

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
        return self::labelledField('textarea', (string)($value ?? ''), $attributes);
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
        return self::labelledField('select', $options, $attributes, true);
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
            // strict comparison on the string form: avoids loose-typing surprises
            // while still matching numeric-string $selected against integer array keys
            if ($selected !== null && (string)$selected === (string)$value) {
                $option_attributes['selected'] =  'selected';
            }

            $options .= new Element('option', (string)$label, $option_attributes);
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
        // remove the internal pseudo-attribute before it can leak into the markup
        $as_input = (($attributes['tag'] ?? null) === 'input');
        unset($attributes['tag'], $attributes['name']);

        $attributes['type'] = 'submit';
        $attributes['id'] ??= $id;

        if ($as_input) {
            $attributes['value'] ??= $label;
            // input is a void element: no inner content, no closing tag
            return (string)(new Element('input', null, $attributes));
        }

        unset($attributes['value']);
        return self::button($label, $attributes);
    }

    /**
     * Generates a labelled HTML form field, combining a form field with a label element.
     *
     * @param string $tag The HTML tag of the form field element
     * @param string|null $content The content of the form field element
     * @param array $attributes Additional attributes to add to the form field element
     * @param bool $trusted_content Whether $content is already-safe markup (e.g. rendered <option>s) that must not be escaped
     * @return string The HTML for the labelled form field
     */
    private static function labelledField(string $tag, ?string $content = null, array $attributes = [], bool $trusted_content = false): string
    {
        // default the id to the field name, but never emit an empty id
        $attributes['id'] ??= $attributes['name'] ?? null;
        if (($attributes['id'] ?? '') === '') {
            unset($attributes['id']);
        }
        $id = $attributes['id'] ?? null;

        // extract internal pseudo-attributes so they are never rendered on the element
        $label_text = $attributes['label'] ?? '';
        $label_wrap = !empty($attributes['label-wrap']);
        unset($attributes['label'], $attributes['label-wrap'], $attributes['tag']);

        // generate the field safely: the default formatter escapes content and attribute values.
        // trusted content (pre-rendered markup) is passed through the identity formatter instead,
        // which would also leave attribute values raw: escape those here first
        if ($trusted_content) {
            $attributes = self::escapedAttributes($attributes);
        }

        $input = (string)(new Element($tag, $content, $attributes, $trusted_content ? false : null));

        if ($label_text === '') {
            return $input;
        }

        // wrap the field in its label when asked, or when there is no id to bind a sibling label to
        if ($label_wrap || $id === null) {
            // escape the label text on its own, then wrap it together with the
            // already-safe field markup as trusted HTML, so nothing is re-escaped
            $safe_label = htmlspecialchars($label_text, ENT_QUOTES);
            return (string)(new Element('label', $safe_label . $input, [], false));
        }

        return self::label($id, $label_text) . $input;
    }

    /**
     * Escapes string attribute values, and string members of array values,
     * the way Element's default formatter would; used when a field is built
     * with the identity formatter to carry trusted, pre-rendered content.
     */
    private static function escapedAttributes(array $attributes): array
    {
        $esc = fn($value) => is_string($value) ? htmlspecialchars($value, ENT_QUOTES) : $value;

        foreach ($attributes as $name => $value) {
            $attributes[$name] = is_array($value) ? array_map($esc, $value) : $esc($value);
        }

        return $attributes;
    }
}
