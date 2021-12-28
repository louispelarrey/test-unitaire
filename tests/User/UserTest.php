<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Services\ToDoList as ToDoListService;
use App\Services\ArrayUtils;

class UserTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIsValidWithGoodInfos()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertTrue($user->isValid());
    }

    public function testIsNotValidWithBadEmail()
    {
        $user = new User(
            "uuu",
            "Pelarrey",
            "Louis",
            "2000-05-21",
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }

    public function testIsNotValidWithNullLastName()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            null,
            "Louis",
            "2000-05-21",
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }

    public function testIsNotValidWithNullFirstName()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            null,
            "2000-05-21",
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }

    public function testIsNotValidWithUnderage()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            "2018-05-21",
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }

    public function testIsNotValidWithNoLastName()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "",
            "Louis",
            "2000-05-21",
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }

    public function testIsWorkingWithNullBirthDate()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            null,
            "azerty",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }

    public function testIsNotWorkingWithNoPassword()
    {
        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "",
            new ToDoListService(new ArrayUtils())
        );
        $this->assertFalse($user->isValid());
    }
}
