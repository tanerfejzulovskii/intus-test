<?php

namespace App\Services;

use App\Contracts\UrlValidatorInterface;
use GuzzleHttp\Client;

class GoogleSafeBrowsingUrlValidator implements UrlValidatorInterface
{
    /**
     * $client
     *
     * @var Client
     */
    protected $client;

    /**
     * __construct
     *
     * @param Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Validate URL with Google Safe Browsing
     *
     * @param string $url
     * @return void
     */
    public function validate(string $url): bool
    {
        $googleApiKey = config('services.google.api_key');
        $uri = config('services.google.safe_browsing_url') . "threatMatches:find?key=$googleApiKey";

        $response = $this->client->post($uri, [
            'json' => [
                'client' => [
                    'clientId' => 'url-shortener',
                    'clientVersion' => '1.0.0'
                ],
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [
                        ['url' => $url]
                    ]
                ]
            ],
            'verify' => false
        ]);

        $body = json_decode($response->getBody(), true);

        return empty($body['matches']);
    }
}
