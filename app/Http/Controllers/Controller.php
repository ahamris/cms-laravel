<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use MetaTag;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Defaults
        MetaTag::set('title', get_setting('site_name'));
        MetaTag::set('description', get_setting('site_description'));
        MetaTag::set('image', get_image(get_setting('site_logo')));

        MetaTag::set('robots', 'all,index,follow');
        MetaTag::set('googlebot', 'all,index,follow');
        MetaTag::set('rating', 'general');
        MetaTag::set('distribution', 'global');
    }
}
