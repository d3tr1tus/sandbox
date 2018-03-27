<?php

namespace App\Service\Utils;

use App\Exception\Service\Utils\TimesOverlapsException;
use App\Exception\Service\Utils\TimesRangeNotValidException;

class WorkingTimeConvert
{

    /**
     * konvertuje pracovni dobu na volny cas
     *
     * vstupni pole musi byt ve formatu:
     * [
     *  ['8:00', '14:30'],
     *  ['18:00', '24:00']
     * ]
     *
     * @param array $workingTimes
     * @return array
     * @throws TimesOverlapsException
     * @throws TimesRangeNotValidException
     */
    public static function convertToFreeTimes(array $workingTimes): array
    {
        $freeTimes = self::convert($workingTimes);
        // je li vystupni pole prazdne znamena to ze je cely den volno, proto se to musi do pole nastavit
        return count($freeTimes) == 0 ? $freeTimes[] = ['0:00', '24:00'] : $freeTimes;
    }

    /**
     * konvertuje pole volnych casu na Pracovni casy
     *
     * vstupni pole musi byt ve formatu:
     * [
     *  ['8:00', '14:30'],
     *  ['18:00', '24:00']
     * ]
     *
     * @param array $freeTimes
     * @return array
     * @throws TimesOverlapsException
     * @throws TimesRangeNotValidException
     */
    public static function convertToWorkingTimes(array $freeTimes): array
    {
        return self::convert($freeTimes);
    }

    /**
     * @param array $inTimes
     * @return array
     * @throws TimesOverlapsException
     * @throws TimesRangeNotValidException
     */
    private static function convert(array $inTimes): array
    {
        $startTime = '0:00';
        $endTime = '24:00';
        $outTimes = [];

        //seradi pole casu "od" vzestupne
        usort($inTimes, ['App\Service\Utils\WorkingTimeConvert', 'subTimesSort']);

        $i = 0;
        foreach ($inTimes as $inTime) {
            if (!((strtotime($inTime[1]) - strtotime($inTime[0])) > 0)) {
                throw new TimesRangeNotValidException();
            }

            $i++;
            $outTime = [];

            if ((strtotime($inTime[0]) - strtotime($startTime)) < 0) {
                throw new TimesOverlapsException();
            } elseif (strtotime($inTime[0]) == strtotime($startTime)) {
                $startTime = $inTime[1];
            } else {
                $outTime[0] = $startTime;
                $outTime[1] = $inTime[0];
                $outTimes[] = $outTime;
                $startTime = $inTime[1];
            }

            if ($i == count($inTimes)) {
                if ((strtotime($endTime) - strtotime($inTime[1])) > 0) {
                    $outTime = [];
                    $outTime[0] = $inTime[1];
                    $outTime[1] = $endTime;
                    $outTimes[] = $outTime;
                }
            }
        }
        return $outTimes;
    }

    /**
     * podfunkce usort pro serazeni poli v poli
     *
     * @param $a
     * @param $b
     * @return false|int
     */
    private static function subTimesSort($a, $b)
    {
        return strtotime($a[0]) - strtotime($b[0]);
    }
}