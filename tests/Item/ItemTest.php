<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\Item;

class ItemTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testItemWithValidInfos()
    {
        $item = new Item(
            "Courses",
            "Salade, Tomate, Oignon",
            "2020-10-10 00:00:00"
        );
        $this->assertEquals("Courses", $item->getName());
        $this->assertEquals("Salade, Tomate, Oignon", $item->getContent());
        $this->assertEquals("2020-10-10 00:00:00", $item->getDateCreation());
    }

    public function testItemWithNonValidName()
    {
        $item = new Item(
            "",
            "Salade, Tomate, Oignon",
            "2020-10-10 00:00:00"
        );
        $this->assertNotEquals("Courses", $item->getName());
    }

    public function testItemWithNonValidContent()
    {
        $item = new Item(
            "Courses",
            "",
            "2020-10-10 00:00:00"
        );
        $this->assertNotEquals("Salade, Tomate", $item->getContent());
    }

    public function testItemWithNonValidDateCreation()
    {
        $item = new Item(
            "Courses",
            "Salade, Tomate",
            ""
        );
        $this->assertNotEquals("2020-10-10 00:00:00", $item->getDateCreation());
    }
}
