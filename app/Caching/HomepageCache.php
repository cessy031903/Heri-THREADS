<?php

namespace App\Caching;

use Illuminate\Support\Facades\Cache;

/**
 * Invalidates the homepage's cached counts/showcase whenever a Dance or
 * Attire is created, updated, or deleted from the admin panel.
 */
class HomepageCache
{
    public static function flush(): void
    {
        Cache::forget('home.dance-count');
        Cache::forget('home.attire-count');
        Cache::forget('home.showcase-items');
    }
}
