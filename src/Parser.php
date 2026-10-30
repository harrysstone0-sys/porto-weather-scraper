<?php

declare(strict_types=1);

namespace PortoWeather;

final class Parser
{
    /**
     * Pull the current temperature (Celsius) out of an IPMA page.
     * IPMA renders it in a span with a "temperatura" class; the markup
     * changes now and then, so the pattern stays loose on purpose.
     */
    public static function temperature(string $html): ?float
    {
        if (preg_match('/class="[^"]*temperatura[^"]*"[^>]*>\s*(-?\d+(?:[.,]\d+)?)\s*(?:\xC2\xB0C|&deg;C)?/iu', $html, $m)) {
            return (float) str_replace(',', '.', $m[1]);
        }
        return null;
    }
}
