<?php

namespace App\Entity;

use DateTime;

class Item
{
    public ?string $name;
    public ?string $content;
    public ?string $dateCreation;

    public function __construct(?string $name, ?string $content, ?string $dateCreation)
    {
        $this->name = $name;
        $this->content = $content;
        $date = new DateTime("$dateCreation");
        $this->dateCreation = $date->format('Y-m-d H:i:s');;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }
}
