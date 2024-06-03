<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class UrlController extends Controller
{
    public function shorten(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $client = new Client(['verify' => false]);
        $googleApiKey = config('services.google.api_key');
        $uri = config('services.google.safe_browsing_url') . "threatMatches:find?key=$googleApiKey";

        $response = $client->post($uri, [
            'json' => [
                'client' => [
                    'clientId' => 'short-url',
                    'clientVersion' => '1.0.0'
                ],
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [
                        ['url' => $request->url]
                    ]
                ]
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        if (!empty($body['matches'])) {
            return response()->json(['error' => 'URL is unsafe'], 400);
        }

        $existingUrl = Url::where('primary_url', $request->url)->first();
        if ($existingUrl) {
            return response()->json(['shortUrl' => url("short-urls/$existingUrl->short_url")]);
        }

        $shortUrl = Str::random(6);
        Url::create([
            'primary_url' => $request->url,
            'short_url' => $shortUrl
        ]);

        return response()->json(['shortUrl' => url("short-urls/$shortUrl")]);
    }

    public function redirect($shortUrl)
    {
        $url = Url::where('short_url', $shortUrl)->firstOrFail();

        return redirect($url->primary_url);
    }
}
