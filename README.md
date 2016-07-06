Automatic Paragraph Plugin for Mecha CMS
========================================

Paragraph
---------

The `Converter\Paragraph` class will convert double line break to `<p> … </p>` and single line break to `<br>`.

### Before

~~~ .html
Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet.
Lorem ipsum dolor sit amet.

<pre><code>Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet.</code></pre>

<blockquote>
Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet.
</blockquote>

<p>Lorem ipsum dolor sit amet.</p>
~~~

### After

~~~ .html
<p>Lorem ipsum dolor sit amet.</p>
<p>Lorem ipsum dolor sit amet.<br>
Lorem ipsum dolor sit amet.</p>
<pre><code>Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet.</code></pre>
<blockquote>
<p>Lorem ipsum dolor sit amet.</p>
<p>Lorem ipsum dolor sit amet.</p>
</blockquote>
<p>Lorem ipsum dolor sit amet.</p>
~~~

---

### Class Usage

~~~ .php
require '../workers/converter.paragraph.php';

$parser = new Converter\Paragraph();
$parser->ignore = 'pre|code'; // settings ...

echo $parser->run('Lorem ipsum dolor sit amet.');
~~~