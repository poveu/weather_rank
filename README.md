# Weather rank
This is an API for a small app that's fetching weather data for specified cities, and then ordering them by "the best" weather.

## Demo
You can see the API in this example request URL:
https://weather.poveu.pl/?cities=Gdansk,Warsaw,San%20Francisco,Tokio

## Getting started
1. Run command `composer install`

2. Create `.env.local` file in root directory and fill it with OpenWeather data:
    ```
    OPEN_WEATHER_API_KEY=your_api_key
    OPEN_WEATHER_API_URL=https://api.openweathermap.org/data/2.5/weather
    ```

3. (in dev environment) Install Symfony (https://symfony.com/download) and start dev server with command `symfony server:start`

## Usage
#### API parameters:
- **cities**: comma separated list of cities to compare

#### Example API request URL on local server:
```
http://127.0.0.1:8000/?cities=Warsaw,San Francisco,Gdansk
```
