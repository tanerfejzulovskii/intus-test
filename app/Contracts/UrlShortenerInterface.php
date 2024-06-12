<?php

namespace App\Contracts;

interface UrlShortenerInterface
{
    public function shorten(string $url): string;
}
