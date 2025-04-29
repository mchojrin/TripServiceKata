<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Trip\TripService;

class TripServiceShould extends TestCase
{
    /**
     * @test
     */
    public function do_something(): void
    {
        $tripService = new TripService();
        $this->assertEquals([], $tripService->getTripsByUser(null));
    }
}
