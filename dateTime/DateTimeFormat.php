<?php

namespace levitarmouse\core\dateTime;

/**
 * Description of formatDate
 *
 * @author gabriel
 */
class DateTimeFormat {

    public static function date($date, $currFromat = 'd-m-Y', $returnFormat = 'Y-M-d') {

        $result = $date;

        $day = $month = $year = '';
        if ($currFromat == 'd/m/Y') {
            list($day, $month, $year) = explode('/', $date);
        }
        if ($currFromat == 'd-m-Y') {
            list($day, $month, $year) = explode('-', $date);
        }

        switch ($returnFormat) {
            case 'Y-M-d':
                $year = str_pad($year, 4, '20', STR_PAD_LEFT);
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $day = str_pad($day, 2, '0', STR_PAD_LEFT);

                $result = $year . '-' . $month . '-' . $day;
                break;
            case 'Y/M/d':
                $year = str_pad($year, 4, '20', STR_PAD_LEFT);
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $day = str_pad($day, 2, '0', STR_PAD_LEFT);

                $result = $year . '/' . $month . '/' . $day;
                break;
        }

        return $result;
    }

}
