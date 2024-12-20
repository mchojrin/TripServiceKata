<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\Trip;
use TripServiceKata\Trip\TripDAO;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;
use TripServiceKata\User\UserBuilder;

class TripServiceShould extends TestCase
{
    const VISITOR_NAME = "A visitor";
    const FRIEND_NAME = "A friend";
    const GUEST = null;
    const ANOTHER_FRIEND_NAME = "Another friend";
    private $registeredUser;

    protected function setUp()
    {
        parent::setUp();
        $this->registeredUser = new User(self::VISITOR_NAME);
    }

    /**
     * @test
     */
    public function throw_exception_if_user_is_not_logged_in(): void
    {
        $this->expectException(UserNotLoggedInException::class);
        TripService::newInstance(self::GUEST, new TripDAO())->getTripsByUser(new User(self::FRIEND_NAME));
    }

    /**
     * @test
     * @throws UserNotLoggedInException
     */
    public function return_no_trips_if_users_are_not_friends(): void
    {
        $friend = UserBuilder::aUser(self::FRIEND_NAME)
            ->withFriends(new User(self::ANOTHER_FRIEND_NAME))
            ->withTrips(new Trip(), new Trip())
            ->build();

        $this->assertEmpty(TripService::newInstance($this->registeredUser, new TripDAO())->getTripsByUser($friend));
    }

    /**
     * @test
     * @throws UserNotLoggedInException
     */
    public function return_friends_trips_if_users_are_friends(): void
    {
        $toLondon = new Trip();
        $toNYC = new Trip();

        $friend = UserBuilder::aUser(self::FRIEND_NAME)
            ->withFriends($this->registeredUser, new User(self::ANOTHER_FRIEND_NAME))
            ->withTrips($toLondon, $toNYC)
            ->build();

        $tripDAO = $this->createMock(TripDAO::class);
        $tripDAO
            ->expects($this->once())
            ->method("findTripsBy")
            ->with($friend)
            ->willReturn($friend->getTrips());

        $friendsTrips = TripService::newInstance($this->registeredUser, $tripDAO)->getTripsByUser($friend);
        $this->assertCount(2, $friendsTrips);
        $this->assertContains($toLondon, $friendsTrips);
        $this->assertContains($toNYC, $friendsTrips);
    }
}
