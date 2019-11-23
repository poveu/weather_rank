# Demo
You can see the API in this example request URL:
https://weather.paveart.pl/?key=test&cities=Gdansk,Warsaw,San%20Francisco,Tokio

# Getting started
1. Run command `composer install`

2. Create `.env.local` file in root directory and fill it with OpenWeather data:
    ```
    OPEN_WEATHER_API_KEY=your_api_key
    OPEN_WEATHER_API_URL=https://api.openweathermap.org/data/2.5/weather
    ```

3. (optionally) Edit app's API keys in `/src/Controller/WeatherRankController.php`.
Default API key is `test`

4. (in dev environment) Install Symfony (https://symfony.com/download) and start dev server with command `symfony server:start`

# Usage
#### API parameters:
- **key**: your API key
- **cities**: comma separated list of cities to compare

#### Example API request URL:
```
http://127.0.0.1:8000/?key=XXX&cities=Warsaw,San Francisco,Gdansk
```
(replace **key=XXX** with real API key)
