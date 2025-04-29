<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;

class TestableTripService extends TripService
{
    protected function getLoggedUser(): ?User
    {
        return new User('John Doe');
    }
}

class TripServiceShould extends TestCase
{
    /**
     * @test
     */
    public function do_something(): void
    {
        $tripService = new TripService();
        $this->assertEquals([], $tripService->getTripsByUser(new User('Mauro')));
    }
}
