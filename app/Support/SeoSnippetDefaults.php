<?php

namespace App\Support;

/**
 * Sensible defaults for meta title / description / keywords from page title and short intro.
 */
final class SeoSnippetDefaults
{
    /**
     * @var list<string>
     */
    private const STOP_WORDS = [
        'a', 'an', 'and', 'are', 'as', 'at', 'be', 'but', 'by', 'for', 'from', 'has', 'he', 'her', 'his', 'how',
        'i', 'in', 'is', 'it', 'its', 'me', 'my', 'not', 'of', 'on', 'or', 'our', 'so', 'than', 'that', 'the',
        'their', 'them', 'then', 'there', 'these', 'they', 'this', 'to', 'too', 'was', 'we', 'were', 'what',
        'when', 'where', 'which', 'who', 'will', 'with', 'you', 'your',
        'de', 'het', 'een', 'en', 'van', 'voor', 'met', 'naar', 'bij', 'uit', 'over', 'te', 'op', 'als',
    ];

    public static function metaTitle(?string $title, int $max = 60): string
    {
        $t = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $title)));

        return $t === '' ? '' : self::truncateAtWord($t, $max);
    }

    public static function metaDescription(?string $plainText, int $max = 158): string
    {
        $t = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $plainText)));

        return $t === '' ? '' : self::truncateAtWord($t, $max);
    }

    public static function suggestKeywordsFromTitle(?string $title, int $limit = 8): string
    {
        $t = mb_strtolower(strip_tags((string) $title));
        $t = preg_replace('/[^\p{L}\p{N}\s-]+/u', ' ', $t);
        $parts = preg_split('/\s+/u', (string) $t, -1, PREG_SPLIT_NO_EMPTY);
        if ($parts === false || $parts === []) {
            return '';
        }

        $out = [];
        foreach ($parts as $word) {
            $word = trim($word, '-');
            if ($word === '' || mb_strlen($word) < 2) {
                continue;
            }
            if (in_array($word, self::STOP_WORDS, true)) {
                continue;
            }
            if (! in_array($word, $out, true)) {
                $out[] = $word;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return implode(', ', $out);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    public static function fillBlogMetaFromContent(array $validated): array
    {
        if (empty(trim((string) ($validated['meta_title'] ?? '')))) {
            $validated['meta_title'] = self::metaTitle($validated['title'] ?? null);
        }
        if (empty(trim((string) ($validated['meta_description'] ?? '')))) {
            $validated['meta_description'] = self::metaDescription($validated['short_body'] ?? null);
        }
        if (empty(trim((string) ($validated['meta_keywords'] ?? '')))) {
            $validated['meta_keywords'] = self::suggestKeywordsFromTitle($validated['title'] ?? null);
        }

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    public static function fillPageMetaFromContent(array $validated): array
    {
        if (empty(trim((string) ($validated['meta_title'] ?? '')))) {
            $validated['meta_title'] = self::metaTitle($validated['title'] ?? null);
        }
        if (empty(trim((string) ($validated['meta_body'] ?? '')))) {
            $validated['meta_body'] = self::metaDescription($validated['short_body'] ?? null);
        }
        if (empty(trim((string) ($validated['meta_keywords'] ?? '')))) {
            $validated['meta_keywords'] = self::suggestKeywordsFromTitle($validated['title'] ?? null);
        }

        return $validated;
    }

    private static function truncateAtWord(string $text, int $max): string
    {
        if (mb_strlen($text) <= $max) {
            return $text;
        }

        $slice = mb_substr($text, 0, $max);
        $lastSpace = mb_strrpos($slice, ' ');
        if ($lastSpace !== false && $lastSpace > (int) ($max * 0.5)) {
            return rtrim(mb_substr($slice, 0, $lastSpace)).'…';
        }

        return rtrim(mb_substr($text, 0, $max - 1)).'…';
    }
}
