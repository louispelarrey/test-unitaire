<?php

namespace App\Entity;

use DateTime;

class Item
{
    public ?string $name;
    public ?string $content;
    public DateTime $dateCreation;

    public function __construct(?string $name, ?string $content)
    {
        $this->name = $name;
        $this->content = $content;
        $this->dateCreation = new DateTime();
    }
}
