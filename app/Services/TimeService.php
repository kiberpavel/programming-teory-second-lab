<?php

namespace App\Services;

/**
 * Class TimeService.
 */
class TimeService
{
    /**
     * @var float время начала выполнения скрипта
     */
    private static $start = .0;

    /**
     * Начало выполнения
     */
    public static function start()
    {
        self::$start = microtime(true);
    }

    /**
     * Разница между текущей меткой времени и меткой self::$start
     * @return float
     */
    public static function finish(): float
    {
        return microtime(true) - self::$start;
    }
}
