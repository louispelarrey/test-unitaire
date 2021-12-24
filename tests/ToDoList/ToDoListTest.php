<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\ToDoList as ToDoListService;
use App\Entity\ToDoList as ToDoListEntity;
use App\Entity\Item;
use App\Services\ArrayUtils;

class ToDoListTest extends TestCase {

    public function testIsValidWithGoodInfos()
    {
        $toDoList = new ToDoListService(new ToDoListEntity(), new ArrayUtils());
        $item = new Item(
            "Vaisselle",
            "Laver à froid 10 secondes chaque assiette"
        );
        $item2 = new Item(
            "Vaisselle2",
            "oui"
        );
        $toDoList->add($item);
        $toDoList->add($item2);
        dd($toDoList);
    }
}

//./vendor/phpunit/phpunit/phpunit --testdox -c phpunit.xml