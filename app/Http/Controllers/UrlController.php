<?php

namespace App\Http\Controllers;

use App\Contracts\UrlShortenerInterface;
use App\Contracts\UrlValidatorInterface;
use App\Http\Requests\ShortUrlRequest;
use App\Models\Url;

class UrlController extends Controller
{
    /**
     * $urlValidator
     *
     * @var UrlValidatorInterface
     */
    protected $urlValidator;

    /**
     * $urlShortener
     *
     * @var UrlShortenerInterface
     */
    protected $urlShortener;

    /**
     * __construct
     *
     * @param UrlValidatorInterface $urlValidator
     * @param UrlShortenerInterface $urlShortener
     * @return void
     */
    public function __construct(UrlValidatorInterface $urlValidator, UrlShortenerInterface $urlShortener)
    {
        $this->urlValidator = $urlValidator;
        $this->urlShortener = $urlShortener;
    }

    public function shorten(ShortUrlRequest $request)
    {
        $url = $request->input('url');

        if (!$this->urlValidator->validate($url)) {
            return response()->json(['error' => 'URL is unsafe'], 400);
        }

        $shortUrl = $this->urlShortener->shorten($url);

        return response()->json(['shortUrl' => url("short-urls/$shortUrl")]);
    }

    public function redirect($shortUrl)
    {
        $url = Url::where('short_url', $shortUrl)->firstOrFail();

        return redirect($url->primary_url);
    }
}
