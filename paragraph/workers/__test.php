<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Test</title>
  </head>
  <body>
<?php

$test = <<<EOL
<h3>Basic</h3>

This is some text.

This is some text.

<h3>Tag Check</h3>

<strong>This</strong> is some text started with an inline tag.

<div>This is some text started with a block tag.</div>

This is some text.

<div>This is some text in a block tag.
This is some text in a block tag.</div>

<div>This is some text in a block tag.

This is some text in a block tag.</div>

<div>
This is some text in a block tag.

This is some text in a block tag.
</div>

<div>

This is some text in a block tag.

This is some text in a block tag.

</div>

<h3>Ignore Specific</h3>

<style>
html {}

body {}
</style>

<script>
var foo = 'bar';

var baz = 'qux';
</script>

<pre><code>This is some text.

    Should be ignored.

This is some text.</code></pre>

<h1>
This is some text.

This is some text.
</h1>

This is some text.

This is some text.

<h3>Ignore Comment</h3>

<!--

This is some text.

    Should be ignored.

This is some text.

-->

This is some text.

This is some text.

<h3>Block Quote</h3>

<blockquote>This is some text.</blockquote>

<blockquote>
This is some text.
</blockquote>

<blockquote>

This is some text.

</blockquote>

<blockquote>

This is some text.

This is some text.

</blockquote>

<blockquote>
This is some text.

This is some text.
</blockquote>

This is some text.

<h3>Force Paragraph</h3>

<p>Force paragraph.</p>

<p>

Force paragraph.

</p>

<p>

Force paragraph.

Force paragraph.

</p>

<p>
Force paragraph.

Force paragraph.
</p>

<p class="intro">### Force paragraph.</p>

<p class="intro">

### Force paragraph.

</p>

<p class="intro">

### Force paragraph.

### Force paragraph.

</p>

<p class="intro">
### Force paragraph.

### Force paragraph.
</p>

This is some text.

This is some text.
Break.
Break.
Break.
Break.

<hr>

This is some text.

<img alt="" src="foo.png">

<img alt="" src="foo.png"> this is some text.

<h3>Figure</h3>

<figure>
<img alt="" src="foo.jpg">
<figcaption>Image caption.</figcaption>
</figure>

<figure>
<img alt="" src="foo.jpg">
<figcaption>
Image caption.
</figcaption>
</figure>

<figure>
<img alt="" src="foo.jpg">
<figcaption>

Image caption.

</figcaption>
</figure>

<figure>
<img alt="" src="foo.jpg">
<figcaption>

Image caption.

Image caption.

</figcaption>
</figure>

<figure>
<img alt="" src="foo.jpg">
<figcaption>
Image caption.

Image caption.
</figcaption>
</figure>

This is some text.

<h3>List Item</h3>

<ul>
<li>This is some text.</li>
<li>
This is some text.</li>
<li>
This is some text.
</li>
<li>

This is some text.

</li>
<li>

This is some text.

This is some text.

</li>
<li>
This is some text.

This is some text.
</li>
<li>
<p>Force paragraph.</p>
</li>
</ul>

<h3>Check Tidy</h3>

This is some text.

This is some text.

<hr>

This is some text.

<table border="1">
  <thead>
    <tr>
      <th>Table Header 1</th>
      <th>Table Header 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Table Data 1.1</td>
      <td>Table Data 1.2</td>
    </tr>
    <tr>
      <td>Table Data 2.1</td>
      <td>Table Data 2.2</td>
    </tr>
    <tr>
      <td>
      Table Data 3.1
      </td>
      <td>

      Table Data 3.2

      </td>
    </tr>
    <tr>
      <td>
      Table Data 4.1
      Table Data 4.1
      </td>
      <td>

      Table Data 4.2
      Table Data 4.2

      </td>
    </tr>
    <tr>
      <td>
      Table Data 5.1

      Table Data 5.1
      </td>
      <td>

      Table Data 5.2

      Table Data 5.2

      </td>
    </tr>
  </tbody>
</table>

This is some text.

<h3>Line Break</h3>

This is some text.
<br>
This is some text.
<br>
<br>
This is some text.
<br>
This is some text.
<br>
<br>
This is some text.

<br>
This is some text.

<br>

<br>
This is some text.
EOL;

include __DIR__ . '/converter.paragraph.php';

$parser = new Converter\Paragraph();

echo '<h1>Input</h1><pre>' . htmlspecialchars($test) . '</pre><hr>';
echo '<h1>Output</h1><pre>' . debug($parser->run($test)) . '</pre>';

function debug($text) {
    global $parser;
    $s = '&lt;span style=&quot;display:inline-block;width:100%;margin:1px 0 0;color:black;background-color:';
    $text = preg_replace('#<p(>|\s[^<>]*?>)([\s\S]*?)<\/p>#', $s . 'green;&quot;&gt;<p$1$2</p>&lt;/span&gt;', $text);
    $text = preg_replace('#<(' . $parser->ignore . ')(>|\s[^<>]*?>)([\s\S]*?)<\/\1>#', $s . 'gray;&quot;&gt;<$1$2$3</$1>&lt;/span&gt;', $text);
    $text = preg_replace('#<!--([\s\S]*?)-->#', '&lt;span style=&quot;display:inline-block;width:100%;margin:1px 0 0;background-color:gray;color:black;&quot;&gt;<!--$1-->&lt;/span&gt;', $text);
    $text = htmlspecialchars($text);
    $text = str_replace(array('&amp;lt;', '&amp;gt;', '&amp;quot;'), array('<', '>', '"'), $text);
    return $text;
}

?>
  </body>
</html>