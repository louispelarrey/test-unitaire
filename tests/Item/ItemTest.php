<?php
declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use PHPUnit\Framework\TestCase;
use App\Entity\Item;
use function PHPUnit\Framework\assertFalse;
use App\Services\ToDoList;

class ItemTest extends TestCase {

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testDateCreationIsValid()
    {
        $item = new Item(
            "Test",
            "Random content"
        );
        $todayTimestamp = strtotime("now");
        $this->assertEquals($todayTimestamp, $item->getDateCreation()->getTimestamp());
    }

    public function testNbItems()
    {
        $todoList = new ToDoList();



        for ($i=0; $i<4; $i++) {

            $itemN = 'item' . $i;
            $$itemN = new Item(
                "$i","$i",
                "202$i-01-01"
            );
            $todoList->add($itemN);

        }





        /*$expectedArray = [
            0 => [
                "faire la vaisselle",
                "5mn"
            ],
            1 => [
                ...
            ],
            ...
        ];*/

        dd($todoList->getItems());

        assertTrue($todoList->getItems(), $expectedArray);
    }

}
