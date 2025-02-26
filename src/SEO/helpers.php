<?php

declare(strict_types=1);

use BenBjurstrom\Prezet\SEO\SEOManager;

if (! function_exists('seo')) {
    /**
     * @param array<string, string>|string $key
     * @return SEOManager|string|array<string, string|null>|null
     */
    function seo(string|array|null $key = null): SEOManager|string|array|null
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
