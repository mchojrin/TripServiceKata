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
    public function throw_exception_when_user_is_not_logged_in(): void
    {
        $this->expectException(UserNotLoggedInException::class);
        $tripService = new TestableTripService();
        $this->assertEquals([], $tripService->getTripsByUser(new User('Mauro')));
    }
}
