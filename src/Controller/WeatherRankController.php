<?php


namespace App\Controller;

use App\Service\RankService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * WeatherRank Api Controller.
 */
class WeatherRankController extends AbstractFOSRestController
{
    protected $minCities = 2;
    protected $maxCities = 4;

    protected $rankService;

    /**
     * WeatherRankController constructor.
     * @param RankService $rankService
     */
    public function __construct(RankService $rankService)
    {
        $this->rankService = $rankService;
    }

    /**
     * Rank action
     * @Rest\Get("/")
     * @Rest\Get("/rank")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $cities = $request->query->get('cities');

        // example match: Warsaw,San Francisco,Berlin
        preg_match_all("/\p{L}+(?=[,]*)[\s?\p{L}]+/u", $cities, $regexMatch);
        $citiesArray = $regexMatch[0];

        return $this->processWeatherRankAction($citiesArray);
    }

    /**
     * @param $citiesArray
     * @return Response
     */
    private function processWeatherRankAction($citiesArray): Response
    {
        $citiesCount = count($citiesArray);

        // too less cities
        if (!$citiesCount || $citiesCount < $this->minCities) {
            throw new BadRequestHttpException("You need to specify at least {$this->minCities} cities to compare.");
        }

        // too many cities
        if ($citiesCount > $this->maxCities) {
            throw new BadRequestHttpException("You can compare maximum {$this->maxCities} cities at one time.");
        }

        $rankArray = $this->rankService->getRankData($citiesArray);

        $resultArray = [
            'time' => time(),
            'rank' => $rankArray,
        ];

        return $this->handleView($this->view($resultArray));
    }

}
