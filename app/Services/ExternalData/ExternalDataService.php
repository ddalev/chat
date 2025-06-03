<?php

namespace App\Services\ExternalData;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExternalDataService implements ExternalDataInterface
{
    /**
     * Base URL for the Wikipedia API.
     */
    protected string $baseUrl = 'https://en.wikipedia.org/w/api.php';

    /**
     * Get Wikipedia page content and cache it for 24 hours.
     *
     * @param  string  $pageTitle  The title of the Wikipedia page to retrieve.
     *
     * @return string|null The content of the page, or null if not found or an error occurs.
     */
    public function getWikipediaPage(string $pageTitle): ?string
    {
        $cacheKey = 'wikipedia_page_'.md5($pageTitle);

        $cachedValue = Cache::get($cacheKey);

        if ($cachedValue !== null) {
            return $cachedValue;
        }

        try {
            $response = Http::get($this->baseUrl, [
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
                $extract = $page['extract'] ?? null;

                if ($extract !== null) {
                    Cache::put($cacheKey, $extract, now()->addDay());
                }

                return $extract;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['page_title' => $pageTitle]);

            // Quietly handle the error and return null.
            return null;
        }

        return null;
    }
}
