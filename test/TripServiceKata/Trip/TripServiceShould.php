<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\Trip;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;

class TestableTripService extends TripService
{
    public $loggedUser;

    protected function getLoggedUser(): ?User
    {
        return $this->loggedUser;
    }

    protected function findTripsByUser(User $user)
    {
        return $user->getTrips();
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

    /**
     * @test
     */
    public function return_no_trips_when_user_has_no_friends(): void
    {
        $tripService = new TestableTripService();
        $tripService->loggedUser = new User('John');
        $aUser = new User('Mauro');
        $aTrip = new Trip();
        $aUser->addTrip($aTrip);
        $this->assertEquals([], $tripService->getTripsByUser($aUser));
    }
}
