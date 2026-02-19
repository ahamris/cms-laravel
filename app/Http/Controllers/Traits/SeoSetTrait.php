<?php

namespace App\Http\Controllers\Traits;

use Siberfx\LaraMeta\Facades\MetaTag;

trait SeoSetTrait
{
    public function setSeoTags(array $keys = [])
    {

        MetaTag::set('title', $keys['google_title']);
        MetaTag::set('description', $keys['google_description']);
        MetaTag::set('image', $keys['google_image'] ?? null);
        MetaTag::set('keywords', $keys['keywords'] ?? get_setting('meta_keywords'));
    }
}
