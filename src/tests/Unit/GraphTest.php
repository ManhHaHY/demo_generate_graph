<?php

namespace Tests\Unit;

use App\Interfaces\GraphInterface;
use App\Models\Graph;
use App\Repositories\GraphRepository;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class GraphTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetDataFromUrl()
    {
        $testUrl = 'https://en.wikipedia.org/wiki/Men%27s_60_metres_world_record_progression';
        $this->assertSame(
            [
                [
                    '6.6',
                    '26 February 1938'
                ],
                'type' => 'Time',
                [
                    '23 February 1935'
                ]
            ],
            $this->app->make(GraphRepository::class)->getDataFromUrl($testUrl)
        );
    }

    /**
     * @return void
     */
    public function testGetHeader()
    {
        $crawler = $this->mock(Crawler::class);
        $crawler->shouldReceive('filter')
            ->andReturnSelf();
        $crawler->shouldReceive('each')
            ->andReturn([])->once();
        $this->assertSame(
            [],
            $this->app->make(GraphRepository::class)->getHeaders($crawler)
        );
    }
}
