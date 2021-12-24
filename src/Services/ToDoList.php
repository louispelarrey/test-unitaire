<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\ToDoList as ToDoListEntity;

class ToDoList {
    private ArrayUtils $arrayUtils;
    private ToDoListEntity $toDoListEntity;

    public function __construct(ToDoListEntity $toDoListEntity, ArrayUtils $arrayUtils)
    {
        $this->arrayUtils = $arrayUtils;
        $this->toDoListEntity = $toDoListEntity;
    }

    public function getItems(): array
    {
        return $this->toDoListEntity->getItems();
    }

    public function add(?Item $item): bool
    {
        if($this->checkItemsOnAdd($item)) {
            $this->toDoListEntity->add($item);
            return true;
        }

        return false;
    }

    public function checkUniqueNameOnAdd(?array $items, ?Item $item): bool
    {
        if($items !== null) {
            $names = $this->makeTableOfNamesWithItems($items);
            $names[] = $item->name;
            return $this->arrayUtils->checkUniqueNameItem($names);
        }

        return true;
    }

    public function checkMaxCharacters(Item $item): bool
    {
        return $item->content <= 1000;
    }

    /**
     * Vérifie les Items et leur contenus lors de la demande d'ajout
     */
    private function checkItemsOnAdd(?Item $item): bool
    {
        return $this->verifyLastItemDate() && $this->checkLessOrEqualTenItems() &&
            $this->checkUniqueNameOnAdd($this->toDoListEntity->items, $item);
    }

    private function verifyLastItemDate(): bool
    {
        if($this->toDoListEntity->items !== null){
            $itemsLength = count($this->toDoListEntity->items)-1;

            $lastItem = $this->toDoListEntity->items[$itemsLength];
            $now = new \DateTime();
            return true;//$lastItem->dateCreation > $now->modify('-30 minutes');
            //TODO: REMETTRE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }

        return true;
    }

    private function checkLessOrEqualTenItems(): bool
    {
        return $this->toDoListEntity->items === null ? true : count($this->toDoListEntity->items) <= 10;
    }
    
    private function makeTableOfNamesWithItems(array $items): array
    {
        $names = null;
        foreach($items as $item) {
            $names[] = $item->name;
        }

        return $names;
    }
}