<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\ToDoList as ToDoListEntity;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ToDoList {
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
    public function add(?Item $item): bool
    {
        if($this->checkItemsOnAdd($item)) {
            $this->toDoListEntity->add($item);
            return true;
        }

        return false;
    }

    /**
     * Vérifie qu'il n'y a pas de duplicat de l'objet
     */
    public function checkUniqueNameOnAdd(?array $items, ?Item $item): bool
    {
        if($items !== null) {
            $names = $this->makeTableOfNamesWithItems($items);
            $names[] = $item->name;
            return $this->arrayUtils->checkUniqueNameItem($names);
        }

        return true;
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
    private function checkItemsOnAdd(?Item $item): bool
    {
        return $this->verifyLastItemDate() && $this->checkLessOrEqualTenItems() &&
            $this->checkUniqueNameOnAdd($this->toDoListEntity->items, $item);
    }

    /**
     * Vérifie la date du dernier Item inséré
     */
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

    /**
     * Vérifie qu'il y a 10 ou moins Items dans la toDoList
     */
    private function checkLessOrEqualTenItems(): bool
    {
        return $this->toDoListEntity->items === null ? true : count($this->toDoListEntity->items) <= 10;
    }
    
    /**
     * Créé un tableau de noms à partir d'Item
     */
    private function makeTableOfNamesWithItems(array $items): array
    {
        $names = null;
        foreach($items as $item) {
            $names[] = $item->name;
        }

        return $names;
    }
}