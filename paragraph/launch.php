<?php

require __DIR__ . DS . 'workers' . DS . 'converter.paragraph.php';

$parser = new Converter\Paragraph();

function do_paragraph($content, $header) {
    $header = (object) $header;
    if( ! is_string($content) || ! isset($header->content_type) || $header->content_type !== 'HTML') {
        return $content;
    }
    global $parser;
    return str_replace('<br>', '<br' . ES, $parser->run($content));
}

Filter::add(array(
    'content',
    'description',
    'excerpt',
    'message',
), 'do_paragraph', 1.1);