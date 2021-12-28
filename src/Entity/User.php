<?php

namespace App\Entity;

use App\Services\ToDoList as ToDoListService;

class User {
    private $email;
    private $lastName;
    private $firstName;
    //'1970-02-01'
    private $birthDate;
    private $password;
    private $ToDoList;


    public function __construct(?string $email, ?string $lastName, ?string $firstName, ?string $birthDate,
                                ?string $password, ToDoListService $ToDoList)
    {
        $this->email = $email;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
        $this->password = $password;
        $this->ToDoList = $ToDoList;
    }

    public function isValid()
    {
        return $this->checkEmail() && $this->checkName() && $this->checkDateNaissance() && $this->checkPrenom() &&
            $this->checkPassword();
    }

    private function checkEmail()
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    private function checkDateNaissance()
    {
        return isset($this->birthDate) ?
            date_diff(date_create($this->birthDate), date_create('today'))->y >= 13 : null;
    }

    private function checkName()
    {
        return isset($this->lastName) && $this->lastName !== "";
    }

    private function checkPrenom()
    {
        return isset($this->firstName) && $this->firstName !== "";
    }

    private function checkPassword()
    {
        return isset($this->password) && $this->password !== "" && strlen($this->password) >= 8 && strlen($this->password) <= 40 ;
    }
}