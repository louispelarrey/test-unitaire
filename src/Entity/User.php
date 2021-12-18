<?php

namespace App\Entity;

class User {
    public $email;
    public $lastName;
    public $firstName;
    //'1970-02-01'
    public $birthDate;
    public $emailChecker;

    public function __construct($email, $lastName, $firstName, $birthDate)
    {
        $this->email = $email;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
    }

    public function isValid()
    {
        return $this->checkEmail() && $this->checkName() && $this->checkDateNaissance() && $this->checkPrenom();
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
}