<?php

namespace App\Services;

use App\Contracts\UrlShortenerInterface;
use App\Models\Url;
use Illuminate\Support\Str;

class UrlShortener implements UrlShortenerInterface
{
    /**
     * Shorten the given URL
     *
     * @param string $url
     * @return string
     */
    public function shorten(string $url): string
    {
        $existingUrl = Url::where('primary_url', $url)->first();

        if ($existingUrl) {
            return $existingUrl->short_url;
        }

        $shortUrl = $this->generateUniqueShortUrl();
        Url::create([
            'primary_url' => $url,
            'short_url' => $shortUrl
        ]);

        return $shortUrl;
    }

    /**
     * Generate unique short URL
     *
     * @return string
     */
    private function generateUniqueShortUrl(): string
    {
        do {
            $shortUrl = Str::random(6);
        } while (Url::where('short_url', $shortUrl)->exists());

        return $shortUrl;
    }
}
