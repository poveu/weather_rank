<?php


namespace App\Entity;


class City
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var float $temperature
     */
    protected $temperature;

    /**
     * @var float $wind
     */
    protected $wind;

    /**
     * @var int $humidity
     */
    protected $humidity;

    /**
     * @var int $score
     */
    protected $score = 0;


    public function getFieldByName($name)
    {
        return $this->{$name};
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @param mixed $temperature
     */
    public function setTemperature($temperature): void
    {
        $this->temperature = $temperature;
    }

    /**
     * @return mixed
     */
    public function getHumidity()
    {
        return $this->humidity;
    }

    /**
     * @param mixed $humidity
     */
    public function setHumidity($humidity): void
    {
        $this->humidity = $humidity;
    }

    /**
     * @return mixed
     */
    public function getWind()
    {
        return $this->wind;
    }

    /**
     * @param mixed $wind
     */
    public function setWind($wind): void
    {
        $this->wind = $wind;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

}
