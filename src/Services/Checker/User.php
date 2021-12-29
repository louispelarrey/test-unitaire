<?php 

namespace App\Services\Checker;

use App\Entity\User as UserEntity;

class User {
    /**
     * Vérifie via toutes les méthodes de vérification si l'utilisateur est valide
     */
    public static function isValid(UserEntity $user)
    {
        return self::checkEmail($user)
            && self::checkName($user)
            && self::checkDateNaissance($user)
            && self::checkPrenom($user)
            && self::checkPassword($user)
        ;
    }

    /**
     * Vérifie si l'email est bien formaté
     */
    private static function checkEmail(UserEntity $user)
    {
        return filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL);
    }

    /**
     * Vérifie si la date de naissance est supérieure à 13 ans
     */
    private static function checkDateNaissance(UserEntity $user)
    {
        return !is_null($user->getBirthDate()) ?
            date_diff(date_create($user->getBirthDate()), date_create('today'))->y >= 13 : null;
    }

    /**
     * Vérifie si le nom n'est pas vide
     */
    private static function checkName(UserEntity $user)
    {
        return !is_null($user->getLastName())  && $user->getLastName() !== "";
    }

    /**
     * Vérifie si le prénom n'est pas vide
     */
    private static function checkPrenom(UserEntity $user)
    {
        return !is_null($user->getFirstName()) && $user->getFirstName() !== "";
    }

    /**
     * Vérifie si le password est compris entre 8 et 40 caractères et s'il n'est pas vide
     */
    private static function checkPassword(UserEntity $user)
    {
        return !is_null($user->getPassword())
            && $user->getPassword() !== ""
            && strlen($user->getPassword()) >= 8
            && strlen($user->getPassword()) <= 40
        ;
    }
}