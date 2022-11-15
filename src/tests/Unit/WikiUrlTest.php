<?php

namespace Tests\Unit;

use App\Repositories\WikiUrlRepository;
use Tests\TestCase;

class WikiUrlTest extends TestCase
{
    /**
     * Test method check valid url is wiki url
     *
     * @dataProvider WikiUrlDataProvider
     * @param $expected
     * @param $data
     * @return void
     */
    public function testIsWikiUrl($expected, $url)
    {
        $this->assertEquals($expected, $this->app->make(WikiUrlRepository::class)->isWikiUrl($url));
    }

    public function WikiUrlDataProvider()
    {
        return [
            [
                'expected' => true,
                'data' => 'https://en.wikipedia.org/wiki/Women%27s_high_jump_world_record_progression'
            ],
            [
                'expected' => false,
                'data' => 'https://en.google.org/wiki/Women%27s_high_jump_world_record_progression'
            ],
        ];
    }
}
