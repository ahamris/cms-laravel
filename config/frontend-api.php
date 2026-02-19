<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed origins for frontend API
    |--------------------------------------------------------------------------
    |
    | Requests to the frontend API (e.g. /api/pages, /api/blog) are only
    | accepted if the Origin or Referer header matches one of these domains.
    | Use an empty array to allow all origins (no restriction).
    | Use ['*'] or include '*' to allow all (same as empty).
    |
    */

    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', env('FRONTEND_ALLOWED_ORIGINS', ''))))),

];
