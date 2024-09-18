<?php

namespace App\Common;

class Helper
{
    public  static function CheckNumber($data)
    {
        if ($data && floatval($data) != 0) {
            return floatval($data);
        }
        return 0;
    }

    public static function RemoveThousandsSeparator(?string $number): float
    {
        if (!$number) {
            return 0;
        }
        // Remove the thousands separator (comma)
        $cleanNumber = str_replace(',', '', $number);

        // Convert to integer
        return (float) $cleanNumber;
    }
}
