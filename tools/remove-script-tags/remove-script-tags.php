<?php

declare(strict_types=1);

$file_name                    =  $argv[1];
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
/*
$document->loadHTML(
    $file_contents,
    LIBXML_BIGLINES | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET | LIBXML_NOXMLDECL | LIBXML_PARSEHUGE
);
*/

$script_elements    = $document->getElementsByTagName('script');
$elements_to_remove = array();

foreach ($script_elements as $script_element)
{
    $elements_to_remove[] = $script_element;
}

foreach ($elements_to_remove as $element_to_remove)
{
    $element_to_remove->parentNode->removeChild($element_to_remove);
}

$file_contents = mb_convert_encoding(
    $document->saveHTML(),
    'HTML-ENTITIES',
    'UTF-8'
);

file_put_contents($file_name, $file_contents);
