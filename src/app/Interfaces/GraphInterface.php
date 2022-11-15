<?php

namespace App\Interfaces;

use Symfony\Component\DomCrawler\Crawler;

interface GraphInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function getDataFromUrl(string $url): array;

    /**
     * @param string $title
     * @param array $data
     * @return mixed
     */
    public function generateChart(string $title, array $data);

    /**
     * @param Crawler $crawler
     * @return array
     */
    public function getHeaders(Crawler $crawler): array;
}
