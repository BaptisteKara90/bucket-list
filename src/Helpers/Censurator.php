<?php

namespace App\Helpers;

class Censurator
{
    public function purify(string $text){
        $forbbidenWords = [
            'bite',
            'couilles',
            'chatte',
            'cul',
            'Macron',
            'Lepen'
        ];

        return $text = str_ireplace($forbbidenWords, array_map(fn($word) => str_repeat('*', strlen($word)), $forbbidenWords), $text);
    }
}