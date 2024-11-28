<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\Trip;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;

class TestableTripService extends TripService
{

    /**
     * @var User|null
     */
    private $loggedInUser;

    public function __construct(?User $loggedInUser = null)
    {
        $this->loggedInUser = $loggedInUser;
    }

    protected function getLoggedUser(): ?User
    {
        return $this->loggedInUser;
    }
}

class TripServiceShould extends TestCase
{
    const VISITOR_NAME = "A visitor";
    const FRIEND_NAME = "A friend";

    /**
     * @test
     */
    public function throw_exception_if_user_is_not_logged_in(): void
    {
        $this->expectException(UserNotLoggedInException::class);
        $tripService = new TestableTripService(null);
        $tripService->getTripsByUser(new User(self::FRIEND_NAME));
    }

    /**
     * @test
     * @throws UserNotLoggedInException
     */
    public function return_no_trips_if_users_are_not_friends(): void
    {
        $tripService = new TestableTripService(new User(self::VISITOR_NAME));
        $this->assertEmpty($tripService->getTripsByUser(new User(self::FRIEND_NAME)));
    }

    /**
     * @test
     * @throws UserNotLoggedInException
     */
    public function return_friends_trips_if_users_are_friends(): void
    {
        $loggedInUser = new User(self::VISITOR_NAME);
        $tripService = new TestableTripService($loggedInUser);

        $friend = new User(self::FRIEND_NAME);
        $friend->addTrip(new Trip());
        $friend->addTrip(new Trip());
        $friend->addFriend($loggedInUser);

        $this->assertCount(2, $tripService->getTripsByUser($friend));
    }
}
