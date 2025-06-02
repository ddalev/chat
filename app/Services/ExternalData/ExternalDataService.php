<?php

namespace App\Services\ExternalData;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExternalDataService implements ExternalDataInterface
{
    /**
     * Get Wikipedia page content and cache it for 24 hours.
     */
    public function getWikipediaPage(string $pageTitle): ?string
    {
        $cacheKey = 'wikipedia_page_'.md5($pageTitle);

        return Cache::remember($cacheKey, now()->addDay(), function () use ($pageTitle) {
            $response = Http::get('https://en.wikipedia.org/w/api.php', [
                'action' => 'query',
                'prop' => 'extracts',
                'explaintext' => true,
                'format' => 'json',
                'titles' => $pageTitle,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $pages = $data['query']['pages'] ?? [];
                $page = reset($pages);

                return $page['extract'] ?? null;
            }

            return null;
        });
    }
}
