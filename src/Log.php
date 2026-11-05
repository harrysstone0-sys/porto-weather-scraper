<?php

declare(strict_types=1);

namespace PortoWeather;

final class Log
{
    /** Append one reading to a CSV file, creating it (with a header) if needed. */
    public static function append(string $csvPath, string $city, ?float $tempC): void
    {
        $new = !is_file($csvPath);
        $f = fopen($csvPath, 'a');
        if ($f === false) {
            throw new RuntimeException("cannot open {$csvPath}");
        }
        if ($new) {
            fwrite($f, "ts,city,temp_c\n");
        }
        fputcsv($f, [date('c'), $city, $tempC === null ? '' : number_format($tempC, 1, '.', '')]);
        fclose($f);
    }
}
