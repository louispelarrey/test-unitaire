<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\ToDoList as ToDoListEntity;

class ToDoList
{
    private ArrayUtils $arrayUtils;
    private ToDoListEntity $toDoListEntity;

    public function __construct(ArrayUtils $arrayUtils)
    {
        $this->arrayUtils = $arrayUtils;
        $this->toDoListEntity = new ToDoListEntity();
    }

    /**
     * Récupère la liste des items contenus dans la todolist
     */
    public function getItems(): array
    {
        return $this->toDoListEntity->getItems();
    }

    /**
     * Ajoute un Item dans la todolist
     */
    public function add(?Item $item): void
    {
        $this->toDoListEntity->add($item);
        if (!$this->checkItemsOnAdd($item)) {
            $this->toDoListEntity->deleteLast();
        }
    }

    /**
     * Vérifie qu'il n'y a pas de duplicat de l'objet
     */
    public function checkUniqueNameOnAdd(): bool
    {
        $names = $this->getNamesOfItems($this->toDoListEntity->items);
        return $this->arrayUtils->checkUniqueNameItem($names);
    }

    /**
     * Vérifie que le nombre maximum de lettres dans le content n'excède pas 1000
     */
    public function checkMaxCharacters(Item $item): bool
    {
        return $item->content <= 1000;
    }

    /**
     * Vérifie les Items et leur contenus lors de la demande d'ajout
     */
    private function checkItemsOnAdd(): bool
    {
        return $this->verifyLastItemDate() && $this->checkLessOrEqualTenItems() &&
            $this->checkUniqueNameOnAdd();
    }

    /**
     * Vérifie la date du dernier Item inséré
     */
    private function verifyLastItemDate(): bool
    {
        $toDoListLength = count($this->toDoListEntity->items);
        if ($toDoListLength > 1) {
            $previousItem = $this->toDoListEntity->items[$toDoListLength - 2];
            $lastItem = $this->toDoListEntity->items[$toDoListLength - 1];
            $diffByMin = (strtotime($lastItem->dateCreation) - strtotime($previousItem->dateCreation)) / 60;
            return $diffByMin >= 30;
        }
        return true;
    }

    /**
     * Vérifie qu'il y a 10 ou moins Items dans la toDoList
     */
    private function checkLessOrEqualTenItems(): bool
    {
        return count($this->toDoListEntity->items) <= 10;
    }

    /**
     * Récupère les noms des items
     */
    private function getNamesOfItems(array $items): array
    {
        $names = null;
        foreach ($items as $item) {
            $names[] = $item->name;
        }

        return $names;
    }
}