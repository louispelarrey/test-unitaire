<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\Item;

class ItemTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testDateCreationIsValid()
    {
        $item = new Item(
            "Test",
            "Random content",
        );
        $todayTimestamp = strtotime("now");
        $this->assertEquals($todayTimestamp, $item->getDateCreation()->getTimestamp());
    }
}
