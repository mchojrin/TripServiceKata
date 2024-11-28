<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;

class TripService
{
    private $old = true;
    /**
     * @var User
     */
    private $loggedInUser;
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
        
        return $tripService;
    }

    /**
     * @throws UserNotLoggedInException
     */
    public function getTripsByUser(User $friend)
    {
        $this->validateUserLoggedIn();

        return $friend->isFriendOf($this->getLoggedUser()) ? $this->findTripsByUser($friend) : $this->noTrips();
    }

    /**
     * @return User
     */
    protected function getLoggedUser(): ?User
    {
        return $this->old ? UserSession::getInstance()->getLoggedUser() : $this->loggedInUser;
    }

    /**
     * @param User $user
     * @return mixed
     */
    protected function findTripsByUser(User $user): array
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

    private function setLoggedInUser(User $loggedInUser): void
    {
        $this->old = false;
        $this->loggedInUser = $loggedInUser;
    }

    private function setTripDAO(TripDAO $tripDAO): void
    {
        $this->old = false;
        $this->tripDAO = $tripDAO;
    }
}
