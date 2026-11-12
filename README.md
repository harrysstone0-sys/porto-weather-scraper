# porto-weather-scraper

A small CLI that fetches the current temperature for a few Portuguese cities
from IPMA's public weather pages and appends every reading to a CSV.

I keep a cron job pointed at this so I have a year of daily readings before I
start making decisions like "do I need a coat".

## Usage

    php src/WeatherCli.php porto            # prints + logs one reading
    php src/WeatherCli.php porto my.csv     # log to a specific file

`config.json` holds the list of cities the cron job iterates over.

## Notes

- Zero dependencies: PHP >= 8.1 with curl.
- The page layout changes occasionally; `Parser::temperature()` keeps a loose
  pattern on purpose. When it breaks, the exit code is 1 and the CSV row is
  left with an empty temp, so gaps stay visible in the data.
