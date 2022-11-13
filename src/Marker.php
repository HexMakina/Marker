<?php

/**
  * HTML generator
  * Marker, hommage to Christian François Bouche-Villeneuve aka Chris Marker
  *
  * show off @method
  *
  * @method string abbr(string $content, array $attr)
  *                creates an abbreviation or an acronym
  * @method string acronym(string $content, array $attr)
  *                creates an acronym (not supported in HTML5. use abbr() instead)
  * @method string address(string $content, array $attr)
  *                creates contact information for the author/owner of a document
  * @method string applet(string $content, array $attr)
  *                creates an embedded applet
  *                (not supported in HTML5. use embed() or <object> instead)
  * @method string area(string $content, array $attr)
  *                creates an area inside an image map
  * @method string article(string $content, array $attr)
  *                creates an article
  * @method string aside(string $content, array $attr)
  *                creates content aside from the page content
  * @method string audio(string $content, array $attr)
  *                creates embedded sound content
  * @method string b(string $content, array $attr)
  *                creates bold text
  * @method string base(string $content, array $attr)
  *                creates an element that specifies the base url/target for all relative urls in a document
  * @method string basefont(string $content, array $attr)
  *                creates an element that specifies a default color, size, and font for all text in a document
  *                (not supported in HTML5. use CSS instead)
  * @method string bdi(string $content, array $attr)
  *                creates an element that isolates a part of text that might be formatted
  *                in a different direction from other text outside it
  * @method string bdo(string $content, array $attr)
  *                creates an element that overrides the current text direction
  * @method string big(string $content, array $attr)
  *                creates an element that defines big text (not supported in HTML5. use CSS instead)
  * @method string blockquote(string $content, array $attr)
  *                creates a section that is quoted from another source
  * @method string body(string $content, array $attr)
  *                creates the document's body
  * @method string br(string $content, array $attr)
  *                creates a single line break
  * @method string button(string $content, array $attr)
  *                creates a clickable button
  * @method string canvas(string $content, array $attr)
  *                used to draw graphics, on the fly, via scripting (usually javascript)
  * @method string caption(string $content, array $attr)
  *                creates a table caption
  * @method string center(string $content, array $attr)
  *                defines centered text (not supported in HTML5. use CSS instead)
  * @method string cite(string $content, array $attr)
  *                creates the title of a work
  * @method string code(string $content, array $attr)
  *                creates a piece of computer code
  * @method string col(string $content, array $attr)
  *                specifies column properties for each column within a <colgroup> element
  * @method string colgroup(string $content, array $attr)
  *                specifies a group of one or more columns in a table for formatting
  * @method string data(string $content, array $attr)
  *                adds a machine-readable translation of a given content
  * @method string datalist(string $content, array $attr)
  *                specifies a list of pre-defined options for input controls
  * @method string dd(string $content, array $attr)
  *                creates a description/value of a term in a description list
  * @method string del(string $content, array $attr)
  *                creates text that has been deleted from a document
  * @method string details(string $content, array $attr)
  *                creates additional details that the user can view or hide
  * @method string dfn(string $content, array $attr)
  *                specifies a term that is going to be defined within the content
  * @method string dialog(string $content, array $attr)
  *                creates a dialog box or window
  * @method string dir(string $content, array $attr)
  *                defines a directory list (not supported in HTML5. use ul() instead)
  * @method string div(string $content, array $attr)
  *                creates a section in a document
  * @method string dl(string $content, array $attr)
  *                creates a description list
  * @method string dt(string $content, array $attr)
  *                creates a term/name in a description list
  * @method string em(string $content, array $attr)
  *                creates emphasized text
  * @method string embed(string $content, array $attr)
  *                creates a container for an external application
  * @method string fieldset(string $content, array $attr)
  *                groups related elements in a form
  * @method string figcaption(string $content, array $attr)
  *                creates a caption for a <figure> element
  * @method string figure(string $content, array $attr)
  *                specifies self-contained content
  * @method string font(string $content, array $attr)
  *                defines font, color, and size for text    (not supported in HTML5. use CSS instead)
  * @method string footer(string $content, array $attr)
  *                creates a footer for a document or section
  * @method string form(string $content, array $attr)
  *                creates an HTML form for user input
  * @method string frame(string $content, array $attr)
  *                defines a window (a frame) in a frameset (not supported in HTML5)
  * @method string frameset(string $content, array $attr)
  *                defines a set of frames   (not supported in HTML5)
  * @method string h1(string $content, array $attr)
  *                creates HTML headings 1
  * @method string h2(string $content, array $attr)
  *                creates HTML headings 2
  * @method string h3(string $content, array $attr)
  *                creates HTML headings 3
  * @method string h4(string $content, array $attr)
  *                creates HTML headings 4
  * @method string h5(string $content, array $attr)
  *                creates HTML headings 5
  * @method string h6(string $content, array $attr)
  *                creates HTML headings 6
  * @method string head(string $content, array $attr)
  *                contains metadata/information for the document
  * @method string header(string $content, array $attr)
  *                creates a header for a document or section
  * @method string hr(string $content, array $attr)
  *                creates a thematic change in the content
  * @method string html(string $content, array $attr)
  *                creates the root of an HTML document
  * @method string i(string $content, array $attr)
  *                creates a part of text in an alternate voice or mood
  * @method string iframe(string $content, array $attr)
  *                creates an inline frame
  * @method string input(string $content, array $attr)
  *                creates an input control
  * @method string ins(string $content, array $attr)
  *                creates a text that has been inserted into a document
  * @method string kbd(string $content, array $attr)
  *                creates keyboard input
  * @method string label(string $content, array $attr)
  *                creates a label for an <input> element
  * @method string legend(string $content, array $attr)
  *                creates a caption for a <fieldset> element
  * @method string li(string $content, array $attr)
  *                creates a list item
  * @method string link(string $content, array $attr)
  *                creates the relationship between a document and an external resource
  *                (most used to link to style sheets)
  * @method string main(string $content, array $attr)
  *                specifies the main content of a document
  * @method string map(string $content, array $attr)
  *                creates an image map
  * @method string mark(string $content, array $attr)
  *                creates marked/highlighted text
  * @method string meta(string $content, array $attr)
  *                creates metadata about an HTML document
  * @method string meter(string $content, array $attr)
  *                creates a scalar measurement within a known range (a gauge)
  * @method string nav(string $content, array $attr)
  *                creates navigation links
  * @method string noframes(string $content, array $attr)
  *                defines an alternate content for users that do not support frames
  *                (not supported in HTML5)
  * @method string noscript(string $content, array $attr)
  *                creates an alternate content for users that do not support client-side scripts
  * @method string object(string $content, array $attr)
  *                creates a container for an external application
  * @method string ol(string $content, array $attr)
  *                creates an ordered list
  * @method string optgroup(string $content, array $attr)
  *                creates a group of related options in a drop-down list
  * @method string option(string $content, array $attr)
  *                creates an option in a drop-down list
  * @method string output(string $content, array $attr)
  *                creates the result of a calculation
  * @method string p(string $content, array $attr)
 *                 creates a paragraph
  * @method string param(string $content, array $attr)
 *                 creates a parameter for an object
  * @method string picture(string $content, array $attr)
 *                 creates a container for multiple image resources
  * @method string pre(string $content, array $attr)
 *                 creates preformatted text
  * @method string progress(string $content, array $attr) represents the progress of a task
  * @method string q(string $content, array $attr)
 *                 creates a short quotation
  * @method string rp(string $content, array $attr)
 *                 creates what to show in browsers that do not support ruby annotations
  * @method string rt(string $content, array $attr)
 *                 creates an explanation/pronunciation of characters (for east asian typography)
  * @method string ruby(string $content, array $attr)
 *                 creates a ruby annotation (for east asian typography)
  * @method string s(string $content, array $attr)
 *                 creates text that is no longer correct
  * @method string samp(string $content, array $attr)
 *                 creates sample output from a computer program
  * @method string script(string $content, array $attr)
 *                 creates a client-side script
  * @method string section(string $content, array $attr)
 *                 creates a section in a document
  * @method string select(string $content, array $attr)
 *                 creates a drop-down list
  * @method string small(string $content, array $attr)
 *                 creates smaller text
  * @method string source(string $content, array $attr)
 *                 creates multiple media resources for media elements (<video> and <audio>)
  * @method string span(string $content, array $attr)
 *                 creates a section in a document
  * @method string strike(string $content, array $attr) defines strikethrough text
  *                (not supported in HTML5. use del() or s() instead)
  * @method string strong(string $content, array $attr)
 *                 creates important text
  * @method string style(string $content, array $attr)
 *                 creates style information for a document
  * @method string sub(string $content, array $attr)
 *                 creates subscripted text
  * @method string summary(string $content, array $attr)
 *                 creates a visible heading for a <details> element
  * @method string sup(string $content, array $attr)
 *                 creates superscripted text
  * @method string svg(string $content, array $attr)
 *                 creates a container for svg graphics
  * @method string table(string $content, array $attr)
 *                 creates a table
  * @method string tbody(string $content, array $attr) groups the body content in a table
  * @method string td(string $content, array $attr)
 *                 creates a cell in a table
  * @method string template(string $content, array $attr)
 *                 creates a container for content that should be hidden when the page loads
  * @method string textarea(string $content, array $attr)
 *                 creates a multiline input control (text area)
  * @method string tfoot(string $content, array $attr) groups the footer content in a table
  * @method string th(string $content, array $attr)
 *                 creates a header cell in a table
  * @method string thead(string $content, array $attr) groups the header content in a table
  * @method string time(string $content, array $attr)
 *                 creates a specific time (or datetime)
  * @method string title(string $content, array $attr)
 *                 creates a title for the document
  * @method string tr(string $content, array $attr)
 *                 creates a row in a table
  * @method string track(string $content, array $attr)
 *                 creates text tracks for media elements (<video> and <audio>)
  * @method string tt(string $content, array $attr)
 *                 creates an element that defines teletype text (not supported in HTML5. use CSS instead)
  * @method string u(string $content, array $attr)
 *                 creates some text that is unarticulated and styled differently from normal text
  * @method string ul(string $content, array $attr)
 *                 creates an unordered list
  * @method string var(string $content, array $attr)
 *                 creates a variable
  * @method string video(string $content, array $attr)
 *                 creates embedded video content
  * @method string wbr(string $content, array $attr)
 *                 creates a possible line-break
  */

namespace HexMakina\Marker;

class Marker
{
    //::span('inner text', $attributes)

    /**
     * @param array<int,string> $arguments
     */
    public static function __callStatic(string $element_type, array $arguments): Element
    {
        $i = 0;
        // first argument is the inner text
        $element_inner = $arguments[$i++] ?? null;
        // second argument, an array for HTML attributes
        $attributes = $arguments[$i++] ?? [];

        return new Element($element_type, $element_inner, $attributes);
    }

    // TODO labels should mandatory, accessibility
    // TODO implement all options of font-awesome
    /**
     * @param array<mixed,mixed> $attributes
     */
    public static function fas(string $icon, string $title = null, array $attributes = []): Element
    {
        $attributes['title'] = $attributes['title'] ?? $title; // attributes take precedence
        $attributes['class'] = sprintf('fas fa-%s %s', $icon, $attributes['class'] ?? '');
        return new Element('i', '', $attributes);
    }

    /**
     * @param array<mixed,mixed> $attributes
     */
    public static function checkbutton(string $field_name, mixed $field_value, string $field_label, array $attributes = []): Element
    {
        if (!isset($attributes['id'])) {
            $attributes['id'] = $field_name;
        }

        if (!isset($attributes['type'])) {
            $attributes['type'] = 'checkbox'; // default
        }

        if (isset($attributes['is_checked']) && $attributes['is_checked'] === true) { // for boolean checkbuttons
            $attributes['checked'] = 'checked';
            unset($attributes['is_checked']);
        }

        return
        Marker::div(
            Marker::label(
                Form::input($field_name, $field_value, $attributes) . Marker::span($field_label),
                ['for' => $attributes['id']]
            ),
            ['class' => 'checkbutton']
        );
    }

    /**
     * @param array<mixed,mixed> $attributes
     */
    public static function img(string $src, string $title, array $attributes = []): Element
    {
        $attributes['src'] = $attributes['src'] ?? $src;
        $attributes['title'] = $attributes['title'] ?? $title;
        return new Element('img', null, $attributes);
    }

    /**
     * @param array<mixed,mixed> $attributes
     */
    public static function a(string $href, string $label, array $attributes = []): Element
    {
        $attributes['href'] = $attributes['href'] ?? $href;
        return new Element('a', $label, $attributes);
    }
}
