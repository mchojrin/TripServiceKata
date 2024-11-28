<?php

declare(strict_types=1);

namespace TripServiceKata\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function should_know_if_its_friend_of_another_user(): void
    {
        $bob = new User("Bob");
        $alice = new User("Alice");

        $this->assertFalse($alice->isFriendOf($bob));

        $bob->addFriend($alice);
        $this->assertTrue($bob->isFriendOf($alice));
    }
}
