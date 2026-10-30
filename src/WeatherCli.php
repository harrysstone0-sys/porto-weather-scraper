<?php

declare(strict_types=1);

require __DIR__ . '/Fetcher.php';
require __DIR__ . '/Parser.php';

use PortoWeather\Fetcher;
use PortoWeather\Parser;

$city = $argv[1] ?? 'porto';
$url = "https://www.ipma.pt/paginas/clima/" . rawurlencode($city) . ".html";

$body = Fetcher::fetch($url);
$t = Parser::temperature($body);

if ($t === null) {
    fwrite(STDERR, "could not find a temperature on the page (layout changed?)\n");
    exit(1);
}

printf("%s: %.1f C\n", ucfirst($city), $t);
