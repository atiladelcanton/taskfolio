<?php

namespace App\Support;

use App\Models\Task;

class Helper
{
    public static function getInitials(string $text): string
    {
        $words = explode(' ', $text);
        $initials = '';

        foreach ($words as $word) {
            if (strlen($word) <= 2) {
                $initials .= substr($word, 0, 2);
            } else {
                $initials .= $word[0];
            }
        }

        return strtoupper($initials);
    }

    public static function generateTaskCode(string $clientName): string
    {
        $initialCodeTask = self::getInitials($clientName);

        return $initialCodeTask.'-'.Task::query()->max('order') + 1;
    }
}
