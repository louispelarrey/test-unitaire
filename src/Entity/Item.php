<?php

namespace App\Entity;

use DateTime;

class Item
{
    public ?string $name;
    public ?string $content;
    private DateTime $dateCreation;

    public function __construct(?string $name, ?string $content)
    {
        $this->name = $name;
        $this->content = $content;
        $this->dateCreation = new DateTime();
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
