<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;

class TripService
{
    /**
     * @throws UserNotLoggedInException
     */
    public function getTripsByUser(User $user)
    {
        $loggedUser = $this->getLoggedUser();
        if ($loggedUser == null) {
            throw new UserNotLoggedInException();
        }

        $isFriend = $this->areFriends($user, $loggedUser);

        return $isFriend ? $this->findTripsByUser($user) : $this->noTrips();
    }

    /**
     * @return User
     */
    protected function getLoggedUser(): ?User
    {
        return UserSession::getInstance()->getLoggedUser();
    }

    /**
     * @param User $user
     * @return mixed
     */
    protected function findTripsByUser(User $user): array
    {
        return TripDAO::findTripsByUser($user);
    }

    /**
     * @return array
     */
    protected function noTrips(): array
    {
        return [];
    }

    /**
     * @param User $user
     * @param User $loggedUser
     * @return bool
     */
    protected function areFriends(User $user, User $loggedUser): bool
    {
        $isFriend = false;
        foreach ($user->getFriends() as $friend) {
            if ($friend == $loggedUser) {
                $isFriend = true;
                break;
            }
        }
        return $isFriend;
    }
}
