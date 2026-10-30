<?php

declare(strict_types=1);

namespace PortoWeather;

final class Fetcher
{
    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36';

    /**
     * Fetch a page the way a normal browser would.
     * Returns the body or throws RuntimeException.
     */
    public static function fetch(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_USERAGENT => self::UA,
            CURLOPT_HTTPHEADER => ['Accept-Language: pt-PT,pt;q=0.9,en;q=0.8'],
        ]);

        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false || $status !== 200) {
            throw new RuntimeException(sprintf('fetch failed for %s (HTTP %d, %s)', $url, $status, $error));
        }

        return $body;
    }
}
