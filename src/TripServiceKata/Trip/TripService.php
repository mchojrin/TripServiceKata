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
        $this->validateUserLoggedIn();

        return $this->areFriends($user, $this->getLoggedUser()) ? $this->findTripsByUser($user) : $this->noTrips();
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
        return $user->isFriendOf($loggedUser);
    }

    /**
     * @return void
     * @throws UserNotLoggedInException
     */
    protected function validateUserLoggedIn(): void
    {
        if ($this->getLoggedUser() == null) {
            throw new UserNotLoggedInException();
        }
    }
}
