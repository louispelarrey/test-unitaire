<?php

namespace App\Entity;

class ToDoList
{
    public array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Ajoute un item au tableau
     */
    public function add(?Item $item): void
    {
        $this->items[] = $item;
    }
}