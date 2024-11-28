<?php

declare(strict_types=1);

namespace TripServiceKata\User;

use TripServiceKata\Trip\Trip;

class UserBuilder
{

    /**
     * @var User[]
     */
    private $friends = [];
    /**
     * @var Trip[]
     */
    private $trips = [];
    /**
     * @var string
     */
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function aUser(string $name): UserBuilder
    {
        return new UserBuilder($name);
    }

    public function withFriends(User ...$friends): UserBuilder
    {
        $this->friends = $friends;

        return $this;
    }

    public function withTrips(Trip ...$trips): UserBuilder
    {
        $this->trips = $trips;
        return $this;
    }

    public function build(): User
    {
        $user = new User($this->name);

        $this->addTripsTo($user);
        $this->addFriendsTo($user);

        return $user;
    }

    private function addTripsTo(User $user): void
    {
        foreach ($this->trips as $trip) {
            $user->addTrip($trip);
        }
    }

    private function addFriendsTo(User $user): void
    {
        foreach ($this->friends as $friend) {
            $user->addFriend($friend);
        }
    }
}