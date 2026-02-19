<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class ComponentFieldExtractor
{
    /**
     * Extract editable fields from component HTML.
     * Extracts all visual elements: headings (h1-h6), text elements (p, span, div, li), images, buttons, links, icons.
     *
     * @return array<string, mixed> Array of extracted fields
     */
    public function extractFields(string $html): array
    {
        $fields = [
            'headings' => [],      // h1-h6
            'texts' => [],         // p, span, div[text], li
            'images' => [],        // img
            'buttons' => [],       // button, a[button-like]
            'links' => [],         // a[href]
            'icons' => [],         // svg, i[icon]
            'ribbon' => null,      // badge/ribbon
        ];

        if (empty($html)) {
            return $fields;
        }

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);

        // Extract all headings (h1-h6)
        $headingNodes = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
        foreach ($headingNodes as $index => $node) {
            $selector = $this->generateSelector($node);
            $fields['headings'][] = [
                'tag' => $node->nodeName,
                'text' => trim($node->textContent),
                'index' => $index,
                'selector' => $selector,
            ];
        }

        // Extract text elements (p, span, div with text, li)
        $textNodes = $xpath->query('//p[not(ancestor::button)] | //span[normalize-space(text())] | //div[normalize-space(text()) and not(descendant::p)] | //li[normalize-space(text())]');
        foreach ($textNodes as $index => $node) {
            $text = trim($node->textContent);
            // Filter out very short text and empty text
            if (! empty($text) && strlen($text) > 5) {
                $selector = $this->generateSelector($node);
                $fields['texts'][] = [
                    'tag' => $node->nodeName,
                    'text' => $text,
                    'index' => $index,
                    'selector' => $selector,
                ];
            }
        }

        // Extract images
        $imageNodes = $xpath->query('//img');
        foreach ($imageNodes as $index => $node) {
            $selector = $this->generateSelector($node);
            $fields['images'][] = [
                'src' => $node->getAttribute('src') ?: '',
                'alt' => $node->getAttribute('alt') ?: '',
                'title' => $node->getAttribute('title') ?: '',
                'index' => $index,
                'selector' => $selector,
            ];
        }

        // Extract buttons (button elements and links that look like buttons)
        $buttonNodes = $xpath->query('//button | //a[contains(@class, "btn")] | //a[contains(@class, "button")] | //a[contains(@class, "rounded-md") and contains(@class, "px-")]');
        foreach ($buttonNodes as $index => $node) {
            $selector = $this->generateSelector($node);
            $fields['buttons'][] = [
                'text' => trim($node->textContent),
                'href' => $node->getAttribute('href') ?: null,
                'type' => $node->nodeName === 'button' ? 'button' : 'link',
                'index' => $index,
                'selector' => $selector,
            ];
        }

        // Extract regular links (not buttons)
        $linkNodes = $xpath->query('//a[@href and not(contains(@class, "btn")) and not(contains(@class, "button"))]');
        foreach ($linkNodes as $index => $node) {
            $text = trim($node->textContent);
            if (! empty($text)) {
                $selector = $this->generateSelector($node);
                $fields['links'][] = [
                    'text' => $text,
                    'href' => $node->getAttribute('href') ?: '',
                    'index' => $index,
                    'selector' => $selector,
                ];
            }
        }

        // Extract icons (svg and icon elements)
        $iconNodes = $xpath->query('//svg | //i[contains(@class, "fa")] | //i[contains(@class, "icon")]');
        foreach ($iconNodes as $index => $node) {
            $selector = $this->generateSelector($node);
            $fields['icons'][] = [
                'tag' => $node->nodeName,
                'class' => $node->getAttribute('class') ?: '',
                'index' => $index,
                'selector' => $selector,
            ];
        }

        // Extract ribbon/badge
        $ribbonNodes = $xpath->query('//*[contains(@class, "badge")] | //*[contains(@class, "ribbon")] | //*[contains(@class, "rounded-full") and contains(@class, "px-")]');
        if ($ribbonNodes->length > 0) {
            $firstRibbon = $ribbonNodes->item(0);
            $selector = $this->generateSelector($firstRibbon);
            $fields['ribbon'] = [
                'text' => trim($firstRibbon->textContent),
                'selector' => $selector,
            ];
        }

        libxml_clear_errors();

        return $fields;
    }

    /**
     * Replace fields in HTML with new values.
     *
     * @param  array<string, mixed>  $fields
     */
    public function replaceFields(string $html, array $fields): string
    {
        if (empty($html)) {
            return $html;
        }

        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);

        // Replace headings (h1-h6)
        if (! empty($fields['headings'])) {
            $headingNodes = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
            foreach ($headingNodes as $index => $node) {
                if (isset($fields['headings'][$index])) {
                    $node->nodeValue = $fields['headings'][$index]['text'] ?? '';
                }
            }
        }

        // Replace text elements
        if (! empty($fields['texts'])) {
            $textNodes = $xpath->query('//p[not(ancestor::button)] | //span[normalize-space(text())] | //div[normalize-space(text()) and not(descendant::p)] | //li[normalize-space(text())]');
            foreach ($textNodes as $index => $node) {
                if (isset($fields['texts'][$index])) {
                    $node->nodeValue = $fields['texts'][$index]['text'] ?? '';
                }
            }
        }

        // Replace images
        if (! empty($fields['images'])) {
            $imageNodes = $xpath->query('//img');
            foreach ($imageNodes as $index => $node) {
                if (isset($fields['images'][$index])) {
                    if (! empty($fields['images'][$index]['src'])) {
                        $node->setAttribute('src', $fields['images'][$index]['src']);
                    }
                    if (isset($fields['images'][$index]['alt'])) {
                        $node->setAttribute('alt', $fields['images'][$index]['alt']);
                    }
                    if (isset($fields['images'][$index]['title'])) {
                        $node->setAttribute('title', $fields['images'][$index]['title']);
                    }
                }
            }
        }

        // Replace buttons
        if (! empty($fields['buttons'])) {
            $buttonNodes = $xpath->query('//button | //a[contains(@class, "btn")] | //a[contains(@class, "button")] | //a[contains(@class, "rounded-md") and contains(@class, "px-")]');
            foreach ($buttonNodes as $index => $node) {
                if (isset($fields['buttons'][$index])) {
                    $node->nodeValue = $fields['buttons'][$index]['text'] ?? '';
                    if ($node->nodeName === 'a' && isset($fields['buttons'][$index]['href'])) {
                        $node->setAttribute('href', $fields['buttons'][$index]['href']);
                    }
                }
            }
        }

        // Replace links
        if (! empty($fields['links'])) {
            $linkNodes = $xpath->query('//a[@href and not(contains(@class, "btn")) and not(contains(@class, "button"))]');
            foreach ($linkNodes as $index => $node) {
                if (isset($fields['links'][$index])) {
                    $node->nodeValue = $fields['links'][$index]['text'] ?? '';
                    if (isset($fields['links'][$index]['href'])) {
                        $node->setAttribute('href', $fields['links'][$index]['href']);
                    }
                }
            }
        }

        // Replace icons (class attribute)
        if (! empty($fields['icons'])) {
            $iconNodes = $xpath->query('//svg | //i[contains(@class, "fa")] | //i[contains(@class, "icon")]');
            foreach ($iconNodes as $index => $node) {
                if (isset($fields['icons'][$index]) && isset($fields['icons'][$index]['class'])) {
                    $node->setAttribute('class', $fields['icons'][$index]['class']);
                }
            }
        }

        // Replace ribbon
        if (isset($fields['ribbon']) && $fields['ribbon'] !== null) {
            $ribbonNodes = $xpath->query('//*[contains(@class, "badge")] | //*[contains(@class, "ribbon")] | //*[contains(@class, "rounded-full") and contains(@class, "px-")]');
            if ($ribbonNodes->length > 0 && isset($fields['ribbon']['text'])) {
                $ribbonNodes->item(0)->nodeValue = $fields['ribbon']['text'];
            }
        }

        libxml_clear_errors();

        // Get the modified HTML
        $newHtml = $dom->saveHTML();

        // Remove the XML declaration if present
        $newHtml = preg_replace('/<\?xml[^>]*\?>/', '', $newHtml);

        return $newHtml ?: $html;
    }

    /**
     * Generate a unique selector for a DOM node.
     * Uses data-mb-id attribute if exists, otherwise creates XPath-like selector.
     */
    private function generateSelector($node): string
    {
        // Try to get existing data-mb-id
        if ($node->hasAttribute('data-mb-id')) {
            return $node->getAttribute('data-mb-id');
        }

        // Generate selector based on tag, class, and position
        $tag = $node->nodeName;
        $class = $node->getAttribute('class');
        $id = $node->getAttribute('id');

        if ($id) {
            return "#{$id}";
        }

        if ($class) {
            $classes = explode(' ', $class);
            $primaryClass = $classes[0] ?? '';
            if ($primaryClass) {
                return ".{$primaryClass}";
            }
        }

        // Fallback to tag name with index
        $parent = $node->parentNode;
        if ($parent) {
            $siblings = $parent->childNodes;
            $index = 0;
            foreach ($siblings as $sibling) {
                if ($sibling === $node) {
                    return "{$tag}:nth-of-type(".($index + 1).')';
                }
                if ($sibling->nodeName === $tag) {
                    $index++;
                }
            }
        }

        return $tag;
    }
}
