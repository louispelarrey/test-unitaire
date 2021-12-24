<?php

namespace App\Services;

class ArrayUtils {

    public function checkUniqueNameItem(array $array): bool
    {
        return count($array) === count(array_unique($array));
    }
}