<?php

namespace App\Contracts;

interface UrlValidatorInterface
{
    public function validate(string $url): bool;
}
