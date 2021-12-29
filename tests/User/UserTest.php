<?php

use App\Services\ToDoList;
use App\Services\User;
use App\Services\Checkers\User as CheckerUser;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

    public function testIsValidWithGoodInfos()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "azertyuiop",
            new ToDoList()
        );

        $this->assertTrue(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotValidWithBadEmail()
    {
        $user = new User(
            "uuu",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "azertyuiop",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotValidWithNoLastName()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "azertyuiop",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotValidWithNoFirstName()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "",
            date("Y-m-d", strtotime('-13 years')),
            "azertyuiop",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotValidWithUnder13()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-12 years')),
            "azertyuiop",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotWorkingWithNoBirthDate()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            "",
            "azertyuiop",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotWorkingWithNoPassword()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }

    public function testIsNotWorkingWith3CharactersPassword()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "aze",
            new ToDoList()
        );

        $this->assertFalse(CheckerUser::isValid($user->getEntity()));
    }
}
