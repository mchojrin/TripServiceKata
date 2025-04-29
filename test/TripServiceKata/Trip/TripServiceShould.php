<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;

class TestableTripService extends TripService
{
    protected function getLoggedUser(): ?User
    {
        return null;
    }
}

class TripServiceShould extends TestCase
{
    /**
     * @test
     */
    public function return_no_trips(): void
    {
        $this->expectException(UserNotLoggedInException::class);
        $tripService = new TestableTripService();
        $this->assertEquals([], $tripService->getTripsByUser(new User('Mauro')));
    }
}
