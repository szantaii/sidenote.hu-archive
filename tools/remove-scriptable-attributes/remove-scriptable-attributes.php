<?php

declare(strict_types=1);

$scriptable_attributes = array(
    'onabort',
    'onafterprint',
    'onbeforeprint',
    'onbeforeunload',
    'onblur',
    'oncanplay',
    'oncanplaythrough',
    'onchange',
    'onclick',
    'oncontextmenu',
    'oncopy',
    'oncuechange',
    'oncut',
    'ondblclick',
    'ondrag',
    'ondragend',
    'ondragenter',
    'ondragleave',
    'ondragover',
    'ondragstart',
    'ondrop',
    'ondurationchange',
    'onemptied',
    'onended',
    'onerror',
    'onfocus',
    'onhashchange',
    'oninput',
    'oninvalid',
    'onkeydown',
    'onkeypress',
    'onkeyup',
    'onload',
    'onloadeddata',
    'onloadedmetadata',
    'onloadstart',
    'onmousedown',
    'onmousemove',
    'onmouseout',
    'onmouseover',
    'onmouseup',
    'onmousewheel',
    'onoffline',
    'ononline',
    'onpagehide',
    'onpageshow',
    'onpaste',
    'onpause',
    'onplay',
    'onplaying',
    'onpopstate',
    'onprogress',
    'onratechange',
    'onreset',
    'onresize',
    'onscroll',
    'onsearch',
    'onseeked',
    'onseeking',
    'onselect',
    'onstalled',
    'onstorage',
    'onsubmit',
    'onsuspend',
    'ontimeupdate',
    'ontoggle',
    'onunload',
    'onvolumechange',
    'onwaiting',
    'onwheel',
);

$file_name                    = $argv[1];
$file_contents                = file_get_contents($file_name);
$document                     = new DOMDocument();
$document->validateOnParse    = false;
$document->resolveExternals   = false;
$document->preserveWhiteSpace = true;
$document->substituteEntities = false;
$document->formatOutput       = false;

$document->loadHTML(
    mb_convert_encoding(
        $file_contents,
        'HTML-ENTITIES',
        'UTF-8'
    ),
    LIBXML_BIGLINES | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET | LIBXML_NOXMLDECL | LIBXML_PARSEHUGE
);

foreach ($scriptable_attributes as $scriptable_attribute)
{
    $xpath        = new DOMXPath($document);
    $query_string = sprintf('//*[@%s]', $scriptable_attribute);
    $nodes        = $xpath->query($query_string);

    foreach ($nodes as $node)
    {
        $node->removeAttribute($scriptable_attribute);
    }
}

$file_contents = mb_convert_encoding(
    $document->saveHTML(),
    'HTML-ENTITIES',
    'UTF-8'
);

file_put_contents($file_name, $file_contents);
