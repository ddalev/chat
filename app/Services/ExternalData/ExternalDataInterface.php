<?php

namespace App\Services\ExternalData;

interface ExternalDataInterface
{
    public function getWikipediaPage(string $pageTitle): ?string;
}
