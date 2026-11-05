<?php

declare(strict_types=1);

namespace PortoWeather;

final class Fetcher
{
    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36';

    /**
     * Fetch with a short retry loop. IPMA sits behind a load balancer and
     * occasionally answers 503 on the first hit; a pause usually clears it.
     */
    public static function fetch(string $url, int $retries = 3): string
    {
        $lastError = 'no attempt made';
        for ($i = 0; $i < $retries; $i++) {
            try {
                return self::attempt($url);
            } catch (RuntimeException $e) {
                $lastError = $e->getMessage();
                usleep(500_000 * ($i + 1));
            }
        }
        throw new RuntimeException("fetch failed after {$retries} tries: {$lastError}");
    }

    private static function attempt(string $url): string
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
