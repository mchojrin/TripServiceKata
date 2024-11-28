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
    public function getTripsByUser(User $user) {
        $loggedUser = $this->getLoggedUser();
        if ($loggedUser != null) {
            $isFriend = false;
            foreach ($user->getFriends() as $friend) {
                if ($friend == $loggedUser) {
                    $isFriend = true;
                    break;
                }
            }
            $tripList = [];
            if ($isFriend) {
                $tripList = $this->findTripsByUser($user);
            }
            return $tripList;
        } else {
            throw new UserNotLoggedInException();
        }
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
}
