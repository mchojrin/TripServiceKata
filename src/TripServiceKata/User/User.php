<?php

namespace TripServiceKata\User;

use TripServiceKata\Trip\Trip;

class User
{
    private $trips;
    private $friends;
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
        $this->trips = array();
        $this->friends = array();
    }

    public function getTrips(): array
    {
        return $this->trips;
    }

    public function getFriends(): array
    {
        return $this->friends;
    }

    public function addFriend(User $user)
    {
        $this->friends[] = $user;
    }

    public function addTrip(Trip $trip)
    {
        $this->trips[] = $trip;
    }

    public function isFriendOf(User $otherUser): bool
    {
        return in_array($otherUser, $this->friends);
    }
}
