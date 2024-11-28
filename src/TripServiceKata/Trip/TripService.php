<?php

namespace TripServiceKata\Trip;

use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;

class TripService
{
    private $old = true;
    /**
     * @var User
     */
    private $loggedUser;
    /**
     * @var TripDAO
     */
    private $tripDAO;

    /**
     * @deprecated
     */
    public function __construct()
    {
    }

    public static function newInstance(?User $loggedInUser, TripDAO $tripDAO): TripService
    {
        $tripService = new self();
        $tripService->setLoggedInUser($loggedInUser);
        $tripService->setTripDAO($tripDAO);
        $tripService->old = false;
        
        return $tripService;
    }

    /**
     * @throws UserNotLoggedInException
     */
    public function getTripsByUser(User $friend)
    {
        $this->validateUserLoggedIn();

        return $friend->isFriendOf($this->getLoggedUser()) ? $this->findTripsBy($friend) : $this->noTrips();
    }

    /**
     * @return User
     */
    private function getLoggedUser(): ?User
    {
        return $this->old ? UserSession::getInstance()->getLoggedUser() : $this->loggedUser;
    }

    /**
     * @param User $user
     * @return mixed
     */
    private function findTripsBy(User $user): array
    {
        return $this->old ? TripDAO::findTripsByUser($user) : $this->tripDAO->findTripsBy($user);
    }

    /**
     * @return array
     */
    protected function noTrips(): array
    {
        return [];
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

    private function setLoggedInUser(?User $loggedInUser): void
    {
        $this->loggedUser = $loggedInUser;
    }

    private function setTripDAO(TripDAO $tripDAO): void
    {
        $this->tripDAO = $tripDAO;
    }
}
