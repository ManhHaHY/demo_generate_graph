<?php

namespace App\Interfaces;

interface WikiUrlInterface
{
    /**
     * @param string $url
     * @return bool
     */
    public function isWikiUrl(string $url): bool;
}
