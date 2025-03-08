<?php

namespace Helpers;

/**
 * Purpose of the Date Handler is to manage any month or date related subjects
 */
class DateHandler
{
    // A maping for the english months to frensh's with a capital
    private static array $enToFr = [
        "january" => "Janvier",
        "february" => "Février",
        "march" => "Mars",
        "april" => "Avril",
        "may" => "Mai",
        "june" => "Juin",
        "july" => "Juillet",
        "august" => "Août",
        "september" => "Septembre",
        "october" => "Octobre",
        "november" => "Novembre",
        "december" => "Décembre"
    ];

    // Mapping of months to number january to 1, feb to 2 ...
    private static array $monthToNum = [
        "january" => 1,
        "february" => 2,
        "march" => 3,
        "april" => 4,
        "may" => 5,
        "june" => 6,
        "july" => 7,
        "august" => 8,
        "september" => 9,
        "october" => 10,
        "november" => 11,
        "december" => 12
    ];

    /**
     * Gets the months of todays' date as a word without capital letter
     *
     * @return string Todays' month as a word without a capital letter
     */
    public static function getTodayMonth(): string
    {
        return strtolower(date("F"));
    }

    /**
     * Get the frensh equivalent with a capital letter of the given month
     *
     * @param string $month The month as a word (ex : january)
     * @return string The frensh equivalent of the word with a capital letter
     */
    public static function getFrenshMonth(string $month): string
    {
        return self::$enToFr[$month];
    }

    /**
     * Get the array mapping of the english months to frensh with a capital letter
     *
     * @return array The mapping (ex : january => Janvier)
     */
    public static function getFrenshMonths(): array
    {
        return self::$enToFr;
    }

    /**
     * Get the number of the month
     * @param string $month The month as a word (ex : january)
     * @return int The number of the month (ex : january => 1)
     */
    public static function getMonthNum(string $month): int
    {
        return self::$monthToNum[$month];
    }

    /**
     * Get the mapping of the numbers of the months
     * @return array (ex : [january => 1, february => 2, ...])
     */
    public static function getMonthsNums(): array
    {
        return self::$monthToNum;
    }
}

