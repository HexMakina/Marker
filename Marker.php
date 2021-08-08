<?php
/**
 * HTML generator
 * Marker, hommage to Christian François Bouche-Villeneuve aka Chris Marker
 */

 /**
  * show off @method
  *
  * @method string abbr(string $innertext, array $attributes) creates an abbreviation or an acronym
  * @method string acronym(string $innertext, array $attributes) creates an acronym (not supported in HTML5. use abbr() instead)
  * @method string address(string $innertext, array $attributes) creates contact information for the author/owner of a document
  * @method string applet(string $innertext, array $attributes) creates an embedded applet (not supported in HTML5. use embed() or <object> instead)
  * @method string area(string $innertext, array $attributes) creates an area inside an image map
  * @method string article(string $innertext, array $attributes) creates an article
  * @method string aside(string $innertext, array $attributes) creates content aside from the page content
  * @method string audio(string $innertext, array $attributes) creates embedded sound content
  * @method string b(string $innertext, array $attributes) creates bold text
  * @method string base(string $innertext, array $attributes) creates an element that specifies the base url/target for all relative urls in a document
  * @method string basefont(string $innertext, array $attributes) creates an element that specifies a default color, size, and font for all text in a document (not supported in HTML5. use CSS instead)
  * @method string bdi(string $innertext, array $attributes) creates an element that isolates a part of text that might be formatted in a different direction from other text outside it
  * @method string bdo(string $innertext, array $attributes) creates an element that overrides the current text direction
  * @method string big(string $innertext, array $attributes) creates an element that defines big text (not supported in HTML5. use CSS instead)
  * @method string blockquote(string $innertext, array $attributes) creates a section that is quoted from another source
  * @method string body(string $innertext, array $attributes) creates the document's body
  * @method string br(string $innertext, array $attributes) creates a single line break
  * @method string button(string $innertext, array $attributes) creates a clickable button
  * @method string canvas(string $innertext, array $attributes)	used to draw graphics, on the fly, via scripting (usually javascript)
  * @method string caption(string $innertext, array $attributes) creates a table caption
  * @method string center(string $innertext, array $attributes) defines centered text (not supported in HTML5. use CSS instead)
  * @method string cite(string $innertext, array $attributes) creates the title of a work
  * @method string code(string $innertext, array $attributes) creates a piece of computer code
  * @method string col(string $innertext, array $attributes) specifies column properties for each column within a <colgroup> element
  * @method string colgroup(string $innertext, array $attributes) specifies a group of one or more columns in a table for formatting
  * @method string data(string $innertext, array $attributes) adds a machine-readable translation of a given content
  * @method string datalist(string $innertext, array $attributes) specifies a list of pre-defined options for input controls
  * @method string dd(string $innertext, array $attributes) creates a description/value of a term in a description list
  * @method string del(string $innertext, array $attributes) creates text that has been deleted from a document
  * @method string details(string $innertext, array $attributes) creates additional details that the user can view or hide
  * @method string dfn(string $innertext, array $attributes) specifies a term that is going to be defined within the content
  * @method string dialog(string $innertext, array $attributes) creates a dialog box or window
  * @method string dir(string $innertext, array $attributes) defines a directory list (not supported in HTML5. use ul() instead)
  * @method string div(string $innertext, array $attributes) creates a section in a document
  * @method string dl(string $innertext, array $attributes) creates a description list
  * @method string dt(string $innertext, array $attributes) creates a term/name in a description list
  * @method string em(string $innertext, array $attributes) creates emphasized text
  * @method string embed(string $innertext, array $attributes) creates a container for an external application
  * @method string fieldset(string $innertext, array $attributes) groups related elements in a form
  * @method string figcaption(string $innertext, array $attributes) creates a caption for a <figure> element
  * @method string figure(string $innertext, array $attributes) specifies self-contained content
  * @method string font(string $innertext, array $attributes) defines font, color, and size for text	(not supported in HTML5. use CSS instead)
  * @method string footer(string $innertext, array $attributes) creates a footer for a document or section
  * @method string form(string $innertext, array $attributes) creates an HTML form for user input
  * @method string frame(string $innertext, array $attributes) defines a window (a frame) in a frameset	(not supported in HTML5)
  * @method string frameset(string $innertext, array $attributes) defines a set of frames	(not supported in HTML5)
  * @method string h1(string $innertext, array $attributes) creates HTML headings 1
  * @method string h2(string $innertext, array $attributes) creates HTML headings 2
  * @method string h3(string $innertext, array $attributes) creates HTML headings 3
  * @method string h4(string $innertext, array $attributes) creates HTML headings 4
  * @method string h5(string $innertext, array $attributes) creates HTML headings 5
  * @method string h6(string $innertext, array $attributes) creates HTML headings 6
  * @method string head(string $innertext, array $attributes) contains metadata/information for the document
  * @method string header(string $innertext, array $attributes) creates a header for a document or section
  * @method string hr(string $innertext, array $attributes) creates a thematic change in the content
  * @method string html(string $innertext, array $attributes) creates the root of an HTML document
  * @method string i(string $innertext, array $attributes) creates a part of text in an alternate voice or mood
  * @method string iframe(string $innertext, array $attributes) creates an inline frame
  * @method string input(string $innertext, array $attributes) creates an input control
  * @method string ins(string $innertext, array $attributes) creates a text that has been inserted into a document
  * @method string kbd(string $innertext, array $attributes) creates keyboard input
  * @method string label(string $innertext, array $attributes) creates a label for an <input> element
  * @method string legend(string $innertext, array $attributes) creates a caption for a <fieldset> element
  * @method string li(string $innertext, array $attributes) creates a list item
  * @method string link(string $innertext, array $attributes) creates the relationship between a document and an external resource (most used to link to style sheets)
  * @method string main(string $innertext, array $attributes) specifies the main content of a document
  * @method string map(string $innertext, array $attributes) creates an image map
  * @method string mark(string $innertext, array $attributes) creates marked/highlighted text
  * @method string meta(string $innertext, array $attributes) creates metadata about an HTML document
  * @method string meter(string $innertext, array $attributes) creates a scalar measurement within a known range (a gauge)
  * @method string nav(string $innertext, array $attributes) creates navigation links
  * @method string noframes(string $innertext, array $attributes) defines an alternate content for users that do not support frames	(not supported in HTML5)
  * @method string noscript(string $innertext, array $attributes) creates an alternate content for users that do not support client-side scripts
  * @method string object(string $innertext, array $attributes) creates a container for an external application
  * @method string ol(string $innertext, array $attributes) creates an ordered list
  * @method string optgroup(string $innertext, array $attributes) creates a group of related options in a drop-down list
  * @method string option(string $innertext, array $attributes) creates an option in a drop-down list
  * @method string output(string $innertext, array $attributes) creates the result of a calculation
  * @method string p(string $innertext, array $attributes) creates a paragraph
  * @method string param(string $innertext, array $attributes) creates a parameter for an object
  * @method string picture(string $innertext, array $attributes) creates a container for multiple image resources
  * @method string pre(string $innertext, array $attributes) creates preformatted text
  * @method string progress(string $innertext, array $attributes) represents the progress of a task
  * @method string q(string $innertext, array $attributes) creates a short quotation
  * @method string rp(string $innertext, array $attributes) creates what to show in browsers that do not support ruby annotations
  * @method string rt(string $innertext, array $attributes) creates an explanation/pronunciation of characters (for east asian typography)
  * @method string ruby(string $innertext, array $attributes) creates a ruby annotation (for east asian typography)
  * @method string s(string $innertext, array $attributes) creates text that is no longer correct
  * @method string samp(string $innertext, array $attributes) creates sample output from a computer program
  * @method string script(string $innertext, array $attributes) creates a client-side script
  * @method string section(string $innertext, array $attributes) creates a section in a document
  * @method string select(string $innertext, array $attributes) creates a drop-down list
  * @method string small(string $innertext, array $attributes) creates smaller text
  * @method string source(string $innertext, array $attributes) creates multiple media resources for media elements (<video> and <audio>)
  * @method string span(string $innertext, array $attributes) creates a section in a document
  * @method string strike(string $innertext, array $attributes) defines strikethrough text	not supported in HTML5. use del() or s() instead.
  * @method string strong(string $innertext, array $attributes) creates important text
  * @method string style(string $innertext, array $attributes) creates style information for a document
  * @method string sub(string $innertext, array $attributes) creates subscripted text
  * @method string summary(string $innertext, array $attributes) creates a visible heading for a <details> element
  * @method string sup(string $innertext, array $attributes) creates superscripted text
  * @method string svg(string $innertext, array $attributes) creates a container for svg graphics
  * @method string table(string $innertext, array $attributes) creates a table
  * @method string tbody(string $innertext, array $attributes) groups the body content in a table
  * @method string td(string $innertext, array $attributes) creates a cell in a table
  * @method string template(string $innertext, array $attributes) creates a container for content that should be hidden when the page loads
  * @method string textarea(string $innertext, array $attributes) creates a multiline input control (text area)
  * @method string tfoot(string $innertext, array $attributes) groups the footer content in a table
  * @method string th(string $innertext, array $attributes) creates a header cell in a table
  * @method string thead(string $innertext, array $attributes) groups the header content in a table
  * @method string time(string $innertext, array $attributes) creates a specific time (or datetime)
  * @method string title(string $innertext, array $attributes) creates a title for the document
  * @method string tr(string $innertext, array $attributes) creates a row in a table
  * @method string track(string $innertext, array $attributes) creates text tracks for media elements (<video> and <audio>)
  * @method string tt(string $innertext, array $attributes) creates an element that defines teletype text (not supported in HTML5. use CSS instead)
  * @method string u(string $innertext, array $attributes) creates some text that is unarticulated and styled differently from normal text
  * @method string ul(string $innertext, array $attributes) creates an unordered list
  * @method string var(string $innertext, array $attributes) creates a variable
  * @method string video(string $innertext, array $attributes) creates embedded video content
  * @method string wbr(string $innertext, array $attributes) creates a possible line-break
  */
namespace HexMakina\Marker;

class Marker
{
  //::span('inner text', $attributes)
  public static function __callStatic($element_type, $arguments)
  {
    $i=0;
    $element_inner =$arguments[$i++]??null;
    $attributes=$arguments[$i++]??[];

    return new Element($element_type, $element_inner, $attributes);
  }

  // TODO labels should mandatory, accessibility
  // TODO implement all options of font-awesome
  public static function fas($icon, $title=null, $attributes=[])
  {
    $attributes['title'] = $attributes['title'] ?? $title; // attributes take precedence
    $attributes['class'] = sprintf('fas fa-%s %s', $icon, $attributes['class'] ?? '');
    return new Element('i','', $attributes);
  }

  public static function checkbutton($field_name, $field_value, $field_label, $attributes=[])
  {
    if(!isset($attributes['id']))
      $attributes['id']=$field_name;

    if(!isset($attributes['type']))
      $attributes['type']='checkbox'; // default

    if(isset($attributes['is_checked']) && $attributes['is_checked'] === true) // for boolean checkbuttons
    {
      $attributes['checked'] = 'checked';
      unset($attributes['is_checked']);
    }

    return
      Marker::div(
        Marker::label(Form::input($field_name, $field_value, $attributes).Marker::span($field_label),
        ['for' => $attributes['id']]),
      ['class'=>'checkbutton']);
  }

  public static function img($src, $title, $attributes=[])
  {
    $attributes['src'] = $attributes['src'] ?? $src;
    $attributes['title'] = $attributes['title'] ?? $title;
    return new Element('img', null, $attributes);
  }

  public static function a($href, $label, $attributes=[])
  {
    $attributes['href'] = $attributes['href'] ?? $href;
    return new Element('a', $label, $attributes);
  }
}
