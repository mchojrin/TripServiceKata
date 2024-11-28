<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;

class TripServiceShould extends TestCase
{
    const FRIEND_NAME = "A friend";

    /**
     * @test
     */
    public function throw_exception_if_user_is_not_logged_in(): void
    {
        $this->expectException(UserNotLoggedInException::class);
        $tripService = new TripService();
        $tripService->getTripsByUser(new User(self::FRIEND_NAME));
    }
}
