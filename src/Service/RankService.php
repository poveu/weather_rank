<?php


namespace App\Service;


use App\Entity\City;
use Exception;

class RankService
{

    protected $openWeatherService;

    protected $citiesObjects = [];
    protected $sortedArrays = [];

    private $rankCategoriess = [
        [
            'name' => 'temperature',
            'weight' => 0.6,
            'bigger_is_better' => true,
        ],
        [
            'name' => 'wind',
            'weight' => 0.3,
            'bigger_is_better' => false,
        ],
        [
            'name' => 'humidity',
            'weight' => 0.1,
            'bigger_is_better' => false,
        ],
    ];

    /**
     * RankService constructor.
     * @param OpenWeatherApiService $openWeatherService
     */
    public function __construct(OpenWeatherApiService $openWeatherService)
    {
        $this->openWeatherService = $openWeatherService;
    }

    /**
     * Sort cities array by selected category
     * @param $category
     * @return array
     */
    private function sortCitiesArrayByCategory($category): array
    {
        $desc = $category['bigger_is_better'];

        $tempArray = $this->citiesObjects;

        usort($tempArray, static function ($city1, $city2) use ($category, $desc) {
            /**
             * @var City $city1
             * @var City $city2
             */
            $result = $city1->getFieldByName($category['name']) > $city2->getFieldByName($category['name']);

            return $desc ? !$result : $result;
        });

        $this->sortedArrays[$category['name']] = $tempArray;

        return $tempArray;
    }

    /**
     * Calculate rank position based on category weight
     * @param City $city
     * @param $category
     * @return int
     */
    private function calculateRankPosition($city, $category): int
    {
        // sort array if it wasn't sorted before
        if (!array_key_exists($category['name'], $this->sortedArrays)) {
            $sortedArray = $this->sortCitiesArrayByCategory($category);
        } else {
            $sortedArray = $this->sortedArrays[$category['name']];
        }

        return array_search($city, $sortedArray, true) + 1;
    }

    /**
     * Calculate city score in specific category
     * @param $rankCategory
     * @param $rankPosition
     * @return int
     */
    private function calculateScoreInCategory($rankCategory, $rankPosition): int
    {
        return (100 - 10 * ($rankPosition - 1)) * $rankCategory['weight'];
    }

    /**
     * Get data for every city in array
     * @param $citiesArray
     * @return array
     */
    public function getRankData($citiesArray): array
    {
        /**
         * Get data from Api
         * @var City $city
         */
        foreach ($citiesArray as $city) {
            $cityObject = $this->getCityObjectFromApi($city);
            // ignore city if api returned no info about this city
            if ($cityObject) {
                $this->citiesObjects[] = $cityObject;
            }
        }

        /**
         * Set rank positions and scores
         * @var City $city
         */
        foreach ($this->citiesObjects as $city) {
            $score = 0;

            foreach ($this->rankCategoriess as $rankCategory) {
                $rankPosition = $this->calculateRankPosition(
                    $city,
                    $rankCategory
                );

                $score += $this->calculateScoreInCategory($rankCategory, $rankPosition);
            }
            $city->setScore($score);
        }

        // Sort results by highest score
        usort($this->citiesObjects, static function ($city1, $city2) {
            /**
             * @var City $city1
             * @var City $city2
             */
            return $city1->getScore() < $city2->getScore();
        });

        return $this->citiesObjects;
    }

    /**
     * Api call
     * @param $city
     * @return City|null
     */
    private function getCityObjectFromApi($city): ?City
    {
        $response = $this->openWeatherService->apiCall($city);
        try {
            $cityData = $response->toArray();

            $city = new City();

            $city->setName($cityData['name']);
            $city->setTemperature($cityData['main']['temp']);
            $city->setWind($cityData['wind']['speed']);
            $city->setHumidity($cityData['main']['humidity']);

            return $city;

        } catch (Exception $e) {
            // ignore city if api returned any error
            return null;
        }
    }


}
