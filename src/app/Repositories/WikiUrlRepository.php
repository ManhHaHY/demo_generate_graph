<?php

namespace App\Repositories;

use App\Interfaces\WikiUrlInterface;
use App\Models\WikiUrl;

class WikiUrlRepository extends WikiUrl implements WikiUrlInterface
{
    /**
     * @param string $url
     * @return bool
     */
    public function isWikiUrl(string $url): bool
    {
        $regex = '/^https?\:\/\/([\w\.]+)wikipedia.org\/wiki\/([\w]+\_?)+/m';
        return preg_match($regex, $url);
    }
}
