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
        if ($loggedUser == null) {
            throw new UserNotLoggedInException();
        } else {
            $isFriend = false;
            foreach ($user->getFriends() as $friend) {
                if ($friend == $loggedUser) {
                    $isFriend = true;
                    break;
                }
            }
            return $isFriend ? $this->findTripsByUser($user) : [];
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
