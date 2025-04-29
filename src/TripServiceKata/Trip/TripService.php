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
        $tripList = array();
        $loggedUser = $this->getLoggedUser();
        $isFriend = false;
        if ($loggedUser != null) {
            foreach ($user->getFriends() as $friend) {
                if ($friend == $loggedUser) {
                    $isFriend = true;
                    break;
                }
            }
            if ($isFriend) {
                $tripList = $this->findTripsByUser($user);
            }
            return $tripList;
        } else {
            throw new UserNotLoggedInException();
        }
    }

    protected function getLoggedUser(): ?User
    {
        return UserSession::getInstance()->getLoggedUser();
    }

    /**
     * @param User $user
     * @return mixed
     */
    protected function findTripsByUser(User $user)
    {
        return TripDAO::findTripsByUser($user);
    }
}
