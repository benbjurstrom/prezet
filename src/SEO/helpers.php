<?php

declare(strict_types=1);

use BenBjurstrom\Prezet\SEO\SEOManager;

if (! function_exists('seo')) {
    function seo(string|array $key = null): SEOManager|string|array|null
    {
        if ($key === null) {
            return app('seo');
        }

        if (is_array($key)) {
            return app('seo')->set($key);
        }

        // String key
        return app('seo')->get($key);
    }
} 