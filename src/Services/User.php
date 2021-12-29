<?php 

namespace App\Services;

use App\Entity\User as EntityUser;

class User {
    private EntityUser $entityUser;

    /**
     * Créé un objet EntityUser, si les informations renseignées sont invalides, throw une Exception
     */
    public function __construct(string $email, string $lastName, string $firstName, string $birthDate,
        string $password, ToDoList $toDoList)
    {        
        $this->entityUser = new EntityUser;
        $this->entityUser
            ->setEmail($email)
            ->setLastName($lastName)
            ->setFirstName($firstName)
            ->setBirthDate($birthDate)
            ->setPassword($password)
            ->setToDoList($toDoList)
        ;
    }

    /**
     * Renvoie l'entité User
     */
    public function getEntity(): EntityUser
    {
        return $this->entityUser;
    }

    public function toArray(): array
    {
        return [
            "email" => $this->entityUser->getEmail(),
            "lastname" => $this->entityUser->getLastName(),
            "firstname" => $this->entityUser->getFirstName(),
            "birthdate" => $this->entityUser->getBirthDate(),
            "password" => $this->entityUser->getPassword(),
            "toDoList" => $this->entityUser->getToDoList()->getItems()
        ];
    }
}