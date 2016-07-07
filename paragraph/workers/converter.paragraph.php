<?php namespace Converter;

// Author: Taufik Nurrohman <https://github.com/tovic>

/**
 * ====================================================================
 *  LINE BREAK TO PARAGRAPH CONVERTER
 * ====================================================================
 *
 * -- CODE: -----------------------------------------------------------
 *
 *    $parser = new Converter\Paragraph();
 *
 *    echo $parser->run('Lorem ipsum dolor sit amet.');
 *
 * --------------------------------------------------------------------
 *
 */

class Paragraph {

    // Skip parsing process if we are in these HTML tag(s)
    public $ignore = 'h[1-6]|kbd|math|pre|script|style|textarea';

    // May or may not contain paragraph tag(s)
    public $auto = 'dd|div|(?:fig)?caption|li|td';

    protected $z = '>|\s[^<>]*?>';
    protected $zz = ' *\/?>|\s[^<>]*? *\/?>';
    protected $b = 'blockquote|div|p';
    protected $i = 'a|abbr|acronym|b|basefont|bdo|big|blink|button|cite|code|del|dfn|em|font|i|img|input|ins|kbd|listing|mar(?:k|quee)|nextid|nobr|q|r[pt]|ruby|s|samp|select|small|spacer|span|strike|strong|su[bp]|svg|textarea|time|tt|u|var|w?br|xm';

    // Run converter ...
    public function run($text) {
        if( ! trim($text)) return $text;
        $_ = '#(<\/?(?:' . $this->ignore . '|p)(?:' . $this->z . ')|<!--[\s\S]*?-->)#';
        $text = str_replace(array("\r\n", "\r"), "\n", $text);
        $text = $this->br($text);
        $parts = preg_split($_, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $text = "";
        $x = 0;
        foreach($parts as $v) {
            if( ! trim($v)) {
                $text .= $v;
                continue;
            }
            if($v[0] === '<' && substr($v, -1) === '>') {
                $x = isset($v[1]) && ($v[1] === '/' || $v[1] === '!') ? 0 : 1;
                $text .= $v; // this is a HTML tag ...
            } else {
                $text .= ! $x ? $this->replace($v) : $v; // process or skip ...
            }
        }
        return $this->tidy($text);
    }

    // Replace character(s) ...
    protected function replace($text) {
        $text = preg_replace('#<(' . $this->b . '|' . $this->auto . ')(' . $this->z . ')\n*#', "<$1$2\n\n", $text);
        $text = preg_replace('#\n*<\/(' . $this->b . '|' . $this->auto . ')>#', "\n\n</$1>", $text);
        $text_array = preg_split('#(\n\s*){2,}#', $text);
        $text = array();
        foreach($text_array as $v) {
            $v = trim($v);
            if($v && (strpos($v, '<') !== 0 || preg_match('#^<(?:' . $this->i . ')[\s>]#', $v))) {
                $v = preg_replace('#\n\s*#', '<br>', $v);
                $text[] = '<p>' . $v . '</p>';
            } else {
                $text[] = $v;
            }
        }
        return $this->fix(implode("\n\n", $text));
    }

    // Convert `<br>` tag(s) ...
    protected function br($text) {
        return preg_replace('#\s*<br(' . $this->zz . ')\s*#', "\n", $text);
    }

    // Fix ...
    protected function fix($text) {
        return preg_replace('#<(' . $this->auto . ')(' . $this->z . ')\n*<p(?:' . $this->z . ')([^\n]*?)<\/p>\n*<\/\1>#', '<$1$2$3</$1>', $text);
    }

    // Tidy ...
    protected function tidy($text) {
        return trim(str_replace(
            '<br>',
            "<br>\n",
            preg_replace(
                array(
                    '#<(\/?[^\s]+?)(' . $this->z . ')#',
                    '#\n*<(\/?(?:' . $this->i . '))(' . $this->z . ')\n*#',
                    '#(^|[^>])\n+\s*<#',
                    '#>\n+\s*([^<]|$)#',
                    '#\n*<(' . $this->ignore . ')(' . $this->z . ')\n*([\s\S]*?)\n*<\/\1>\n*#',
                    '#><(hr|img|input|svg)(' . $this->z . ')<(?!\/)#',
                    '#<\/(' . $this->ignore . ')>\n+<(' . $this->ignore . ')(' . $this->z . ')#',
                    '#<(script|style)(' . $this->z . ')\n*([\s\S]+?)\n*<\/\1>#',
                    '#<([-:\w]+?)(' . $this->z . ')\s*<\/\1>#'
                ),
                array(
                    "\n\n<$1$2\n\n",
                    '<$1$2',
                    '$1<',
                    '>$1',
                    "\n<$1$2$3</$1>\n",
                    ">\n<$1$2\n<",
                    "</$1>\n<$2$3",
                    "<$1$2\n$3\n</$1>",
                    '<$1$2</$1>'
                ),
            $text)
        ));
    }

}