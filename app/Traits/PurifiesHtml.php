<?php

namespace App\Traits;

use Mews\Purifier\Facades\Purifier;

trait PurifiesHtml
{
    /**
     * Purify HTML in the given array for the specified keys.
     * Supports dot notation for nested keys, e.g. 'faqs.*.answer' to purify each faqs[].answer.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $keys  Keys to purify (e.g. ['long_body', 'short_body', 'items.*.content'])
     * @return array<string, mixed>
     */
    protected function purifyHtmlKeys(array $data, array $keys): array
    {
        foreach ($keys as $key) {
            if (str_contains($key, '.*.')) {
                [$parent, $child] = explode('.*.', $key, 2);
                if (! isset($data[$parent]) || ! is_array($data[$parent])) {
                    continue;
                }
                foreach ($data[$parent] as $i => $item) {
                    if (is_array($item) && isset($item[$child]) && is_string($item[$child])) {
                        $data[$parent][$i][$child] = Purifier::clean($item[$child]);
                    }
                }
            } else {
                if (isset($data[$key]) && is_string($data[$key])) {
                    $data[$key] = Purifier::clean($data[$key]);
                }
            }
        }

        return $data;
    }
}
