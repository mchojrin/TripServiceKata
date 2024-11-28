<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;

class TripService
{
    /**
     * @var User
     */
    private $loggedInUser;

    /**
     * @deprecated
     */
    public function __construct()
    {
        $this->setLoggedInUser(UserSession::getInstance()->getLoggedUser());
        $this->setTripDAO(new TripDAO());
    }

    public static function newInstace(User $loggedInUser, TripDAO $tripDAO): TripService
    {
        $tripService = new TripService();
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
        $this->loggedInUser = $loggedInUser;
    }

    private function setTripDAO(TripDAO $tripDAO): void
    {
        $this->tripDAO = $tripDAO;
    }
}
