<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\Exception\DependentClassCalledDuringUnitTestException;

class TripDAO
{
    /**
     * @param User $user
     * @return mixed
     * @deprecated
     */
    public static function findTripsByUser(User $user)
    {
        throw new DependentClassCalledDuringUnitTestException('TripDAO should not be invoked on an unit test.');
    }

    public function findTripsBy(User $user): array
    {
        return self::findTripsByUser($user);
    }
}
