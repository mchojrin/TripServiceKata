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

    protected function findTripsByUser(User $user): array
    {
        return $user->getTrips();
    }
}

class TripServiceShould extends TestCase
{
    const VISITOR_NAME = "A visitor";
    const FRIEND_NAME = "A friend";
    const GUEST = null;
    private $registeredUser;
    private $tripService;

    protected function setUp()
    {
        parent::setUp();
        $this->registeredUser = new User(self::VISITOR_NAME);
        $this->tripService = new TestableTripService($this->registeredUser);
    }

    /**
     * @test
     */
    public function throw_exception_if_user_is_not_logged_in(): void
    {
        $this->expectException(UserNotLoggedInException::class);
        $this->tripService = new TestableTripService(self::GUEST);
        $this->tripService->getTripsByUser(new User(self::FRIEND_NAME));
    }

    /**
     * @test
     * @throws UserNotLoggedInException
     */
    public function return_no_trips_if_users_are_not_friends(): void
    {
        $this->assertEmpty($this->tripService->getTripsByUser(new User(self::FRIEND_NAME)));
    }

    /**
     * @test
     * @throws UserNotLoggedInException
     */
    public function return_friends_trips_if_users_are_friends(): void
    {
        $friend = new User(self::FRIEND_NAME);
        $friend->addTrip(new Trip());
        $friend->addTrip(new Trip());
        $friend->addFriend($this->registeredUser);

        $this->assertCount(2, $this->tripService->getTripsByUser($friend));
    }
}
