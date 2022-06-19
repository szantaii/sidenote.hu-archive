<?php

declare(strict_types=1);

function elementHasAttributesWithValues(DOMElement $element, array $attribute_name_value_pairs): bool
{
    $result = true;

    foreach ($attribute_name_value_pairs as $attribute_name => $attribute_value)
    {
        $value = $element->getAttribute($attribute_name);

        if (!str_contains($value, $attribute_value))
        {
            $result = false;
        }
    }

    return $result;
}

function removeElements(DOMDocument $document, array $elements): DOMDocument
{
    $new_document       = $document;
    $elements_to_remove = array();

    foreach ($elements as $element_name => $attribute_name_value_pair_lists)
    {
        $selected_elements = $new_document->getElementsByTagName($element_name);

        foreach ($selected_elements as $element)
        {
            if ($attribute_name_value_pair_lists === array())
            {
                $elements_to_remove[] = $element;

                continue;
            }

            foreach ($attribute_name_value_pair_lists as $attribute_name_value_pairs)
            {
                if (elementHasAttributesWithValues($element, $attribute_name_value_pairs))
                {
                    $elements_to_remove[] = $element;

                    continue;
                }
            }
        }
    }

    foreach ($elements_to_remove as $element_to_remove)
    {
        $element_to_remove->parentNode->removeChild($element_to_remove);
    }

    return $new_document;
}

function removeStylesBasedOnContents(DOMDocument $document, array $style_contents): DOMDocument
{
    $new_document       = $document;
    $selected_elements  = $new_document->getElementsByTagName('style');
    $elements_to_remove = array();

    foreach ($selected_elements as $element)
    {
        if ($element->getAttribute('type') !== 'text/css'
            || $element->childNodes->count() !== 1
            || !is_string($element->childNodes->item(0)->nodeValue))
        {
            continue;
        }

        foreach ($style_contents as $style_content)
        {
            if (str_contains($element->nodeValue, $style_content))
            {
                $elements_to_remove[] = $element;

                continue;
            }
        }
    }

    foreach ($elements_to_remove as $element_to_remove)
    {
        $element_to_remove->parentNode->removeChild($element_to_remove);
    }

    return $new_document;
}

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

$elements_to_remove = array(
    'link'   => array(
        array(
            'rel'  => 'alternate',
            'type' => 'application/atom',
        ),
        array(
            'rel'  => 'alternate',
            'type' => 'application/json',
        ),
        array(
            'rel'  => 'alternate',
            'type' => 'application/rss',
        ),
        array(
            'rel'  => 'alternate',
            'type' => 'text/xml',
        ),
        array(
            'rel'  => 'dns-prefetch',
        ),
        array(
            'rel'  => 'EditURI',
            'type' => 'application/rsd',
            'href' => 'xmlrpc',
        ),
        array(
            'rel'  => 'https://api.w.org/',
            'href' => 'wp-json/index.html',
        ),
        array(
            'rel'  => 'pingback',
        ),
        array(
            'rel'  => 'profile',
        ),
        array(
            'rel'  => 'publisher',
        ),
        array(
            'rel'  => 'stylesheet',
            'type' => 'text/css',
            'href' => 'http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,400italic,700,700italic&subset=latin,latin-ext',
        ),
        array(
            'rel'  => 'wlwmanifest',
        ),
    ),
    'meta' => array(
        array(
            'name' => 'generator',
        ),
    ),
    'script' => array(),
);
$styles_to_remove = array(
    'wp-smiley',
    'si_captcha',
);

$document = removeElements($document, $elements_to_remove);
$document = removeStylesBasedOnContents($document, $styles_to_remove);

$file_contents = mb_convert_encoding(
    $document->saveHTML(),
    'HTML-ENTITIES',
    'UTF-8'
);

file_put_contents($file_name, $file_contents);
