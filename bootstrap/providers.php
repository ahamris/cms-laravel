<?php

use App\Providers\ActivityLogServiceProvider;
use App\Providers\AiDatabaseConfigServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\BladeComponentsServiceProvider;
use App\Providers\CachedProvider;
use App\Providers\MorphMapServiceProvider;
use App\Providers\TranslationServiceProvider;

return [
    AiDatabaseConfigServiceProvider::class,
    AppServiceProvider::class,
    BladeComponentsServiceProvider::class,
    CachedProvider::class,
    TranslationServiceProvider::class,
    MorphMapServiceProvider::class,
    ActivityLogServiceProvider::class,
];
