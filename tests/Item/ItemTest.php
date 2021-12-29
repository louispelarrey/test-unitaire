<?php
declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use PHPUnit\Framework\TestCase;
use App\Entity\Item;
use function PHPUnit\Framework\assertTrue;
use App\Services\ToDoList;

class ItemTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
    }
}
