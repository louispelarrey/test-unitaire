<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\ToDoList as ToDoListEntity;

class ToDoList
{
    private ToDoListEntity $toDoListEntity;

    public function __construct()
    {
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
        if ($this->checkItemOnAdd($item)) {
            $this->toDoListEntity->add($item);
        }
    }

    private function toDoListLength(): int
    {
        return count($this->toDoListEntity->items);
    }

    /**
     * Vérifie les Items et leur contenus lors de la demande d'ajout
     */
    private function checkItemOnAdd(Item $item): bool
    {
        return  $this->checkLessOrEqualTenItems() && $this->checkUniqueNameOnAdd($item) &&
            $this->checkLastItemDate($item) && $this->checkMaxCharacters($item);
    }

    /**
     * Vérifie
     */
    public function checkEighthAdd(EmailSenderService $emailSenderService): bool
    {
        if ($this->toDoListLength() == 8) {
            return $emailSenderService->sendEmail("unEmail");
        }
        return false;
    }

    /**
     * Vérifie qu'il y a 10 ou moins Items dans la toDoList
     */
    private function checkLessOrEqualTenItems(): bool
    {
        return $this->toDoListLength() <= 10;
    }

    /**
     * Vérifie qu'il n'y a pas de duplicat de l'objet
     */
    private function checkUniqueNameOnAdd(?Item $item): bool
    {
        if ($this->toDoListLength() > 0)
        {
            foreach ($this->toDoListEntity->items as $singleItem) {
                if ($singleItem->name == $item->name)
                    return false;
            }
        }
        return true;
    }

    /**
     * Vérifie la date du dernier Item inséré
     */
    private function checkLastItemDate(?Item $item): bool
    {
        if ($this->toDoListLength() >= 1) {
            $previousItem = $this->toDoListEntity->items[$this->toDoListLength() - 1];
            $diffByMin = (strtotime($item->dateCreation) - strtotime($previousItem->dateCreation)) / 60;
            return $diffByMin >= 30;
        }
        return true;
    }

    /**
     * Vérifie que le nombre maximum de lettres dans le content n'excède pas 1000
     */
    private function checkMaxCharacters(Item $item): bool
    {
        return strlen($item->content) <= 1000;
    }
}

