<?php

declare(strict_types=1);

namespace Rugaard\WeatherKit\DTO\Forecasts;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Collection;
use Rugaard\WeatherKit\DTO\AbstractDTO;
use Rugaard\WeatherKit\DTO\Measurements\CloudCover;
use Rugaard\WeatherKit\DTO\Measurements\Direction;
use Rugaard\WeatherKit\DTO\Measurements\Humidity;
use Rugaard\WeatherKit\DTO\Measurements\Precipitation;
use Rugaard\WeatherKit\DTO\Measurements\Pressure;
use Rugaard\WeatherKit\DTO\Measurements\Temperature;
use Rugaard\WeatherKit\DTO\Measurements\Visibility;
use Rugaard\WeatherKit\DTO\Measurements\Wind;
use Rugaard\WeatherKit\Enums\Precipitation as PrecipitationType;
use Rugaard\WeatherKit\Enums\UVIndex;
use Rugaard\WeatherKit\Enums\WeatherCondition;

/**
 * Hour forecast.
 *
 * @package Rugaard\WeatherKit\DTO\Forecasts
 */
class Hour extends AbstractDTO
{
    /**
     * Timezone of data.
     *
     * @var \DateTimeZone
     */
    protected DateTimeZone $timezone;

    /**
     * Cloud cover measurement.
     *
     * @var \Rugaard\WeatherKit\DTO\Measurements\CloudCover|null
     */
    protected ?CloudCover $cloudCover;

    /**
     * Weather condition.
     *
     * @var \Rugaard\WeatherKit\Enums\WeatherCondition|null
     */
    protected ?WeatherCondition $condition;

    /**
     * Forecast time.
     *
     * @var \DateTime
     */
    protected DateTime $forecastTime;

    /**
     * Whether measurement is during daylight or not.
     *
     * @var bool|null
     */
    protected ?bool $daylight;

    /**
     * Humidity measurement.
     *
     * @var \Rugaard\WeatherKit\DTO\Measurements\Humidity
     */
    protected Humidity $humidity;

    /**
     * Precipitation measurements.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $precipitation;

    /**
     * Pressure measurement.
     *
     * @var \Rugaard\WeatherKit\DTO\Measurements\Pressure
     */
    protected Pressure $pressure;

    /**
     * Temperature measurements.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $temperature;

    /**
     * UV Index measurement.
     *
     * @var \Rugaard\WeatherKit\Enums\UVIndex
     */
    protected UVIndex $uvIndex;

    /**
     * Visibility measurement.
     *
     * @var \Rugaard\WeatherKit\DTO\Measurements\Visibility
     */
    protected Visibility $visibility;

    /**
     * Wind measurements.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $wind;

    /**
     * AbstractDTO constructor.
     *
     * @param array $data
     * @param \DateTimeZone $timezone
     */
    public function __construct(array $data, DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
        parent::__construct($data);
    }

    /**
     * Parse data.
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function parse(array $data): void
    {
        $this->setCloudCover($data['cloudCover'])
             ->setCondition($data['conditionCode'])
             ->setForecastTime($data['forecastStart'])
             ->setHumidity($data['humidity'])
             ->setDaylight($data['daylight'] ?? null)
             ->setPrecipitation($data['precipitationType'], $data['precipitationChance'], $data['precipitationIntensity'] ?? null, $data['precipitationAmount'] ?? null, $data['snowfallIntensity'] ?? null)
             ->setPressure($data['pressure'], $data['pressureTrend'])
             ->setTemperature($data['temperature'], $data['temperatureApparent'], $data['temperatureDewPoint'])
             ->setUVIndex($data['uvIndex'])
             ->setVisibility($data['visibility'])
             ->setWind($data['windSpeed'], $data['windGust'], $data['windDirection']);
    }

    /**
     * Set cloud cover measurement.
     *
     * @param int|float|null $cloudCover
     * @return $this
     */
    public function setCloudCover(int|float|null $cloudCover): self
    {
        $this->cloudCover = $cloudCover !== null ? new CloudCover(['value' => $cloudCover]) : null;
        return $this;
    }

    /**
     * Get cloud cover measurement.
     *
     * @return \Rugaard\WeatherKit\DTO\Measurements\CloudCover|null
     */
    public function getCloudCover(): ?CloudCover
    {
        return $this->cloudCover;
    }

    /**
     * Set weather condition.
     *
     * @param string $condition
     * @return $this
     */
    public function setCondition(string $condition): self
    {
        $this->condition = WeatherCondition::tryFromName($condition);
        return $this;
    }

    /**
     * Get weather condition.
     *
     * @return \Rugaard\WeatherKit\Enums\WeatherCondition|null
     */
    public function getCondition(): ?WeatherCondition
    {
        return $this->condition;
    }

    /**
     * Set forecast time.
     *
     * @param string $forecastTime
     * @return $this
     * @throws \Exception
     */
    public function setForecastTime(string $forecastTime): self
    {
        $this->forecastTime = (new DateTime(datetime: $forecastTime))->setTimezone(timezone: $this->timezone);
        return $this;
    }

    /**
     * Get forecast time.
     *
     * @return \DateTime
     */
    public function getForecastTime(): DateTime
    {
        return $this->forecastTime;
    }

    /**
     * Set whether measurement is during daylight or not.
     *
     * @param bool|null $isDaylight
     * @return $this
     */
    public function setDaylight(?bool $isDaylight): self
    {
        $this->daylight = $isDaylight;
        return $this;
    }

    /**
     * Get whether measurement is during daylight or not.
     *
     * @return bool|null
     */
    public function getDaylight(): ?bool
    {
        return $this->daylight;
    }

    /**
     * Set humidity measurement.
     *
     * @param int|float $humidity
     * @return $this
     */
    public function setHumidity(int|float $humidity): self
    {
        $this->humidity = new Humidity(['value' => $humidity]);
        return $this;
    }

    /**
     * Get humidity measurement.
     *
     * @return \Rugaard\WeatherKit\DTO\Measurements\Humidity
     */
    public function getHumidity(): Humidity
    {
        return $this->humidity;
    }

    /**
     * Set precipitation measurements.
     *
     * @param string $type
     * @param int|float $chance
     * @param int|float $intensity
     * @param int|float $amount
     * @param int|float $snowfall
     * @return $this
     */
    public function setPrecipitation(string $type, int|float $chance, int|float $intensity, int|float $amount, int|float $snowfall): self
    {
        $this->precipitation = Collection::make([
            'type' => PrecipitationType::tryFrom($type),
            'chance' => (float) $chance * 100,
            'intensity' => new Precipitation(['value' => $intensity]),
            'amount' => new Precipitation(['value' => $amount]),
            'snowfall' => new Precipitation(['value' => $snowfall])
        ]);
        return $this;
    }

    /**
     * Get precipitation measurements.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPrecipitation(): Collection
    {
        return $this->precipitation;
    }

    /**
     * Set pressure measurement.
     *
     * @param int|float $pressure
     * @param string $trend
     * @return $this
     */
    public function setPressure(int|float $pressure, string $trend): self
    {
        $this->pressure = new Pressure(['value' => $pressure, 'trend' => $trend]);
        return $this;
    }

    /**
     * Get pressure measurement.
     *
     * @return \Rugaard\WeatherKit\DTO\Measurements\Pressure
     */
    public function getPressure(): Pressure
    {
        return $this->pressure;
    }

    /**
     * Set temperature measurements.
     *
     * @param int|float $value
     * @param int|float $apparent
     * @param int|float $dewPoint
     * @return $this
     */
    public function setTemperature(int|float $value, int|float $apparent, int|float $dewPoint): self
    {
        $this->temperature = Collection::make([
            'value' => new Temperature(['value' => $value]),
            'apparent' => new Temperature(['value' => $apparent]),
            'dewPoint' => new Temperature(['value' => $dewPoint])
        ]);
        return $this;
    }

    /**
     * Get temperature measurements.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTemperature(): Collection
    {
        return $this->temperature;
    }

    /**
     * Set UV index.
     *
     * @param int $uvIndex
     * @return $this
     */
    public function setUVIndex(int $uvIndex): self
    {
        $this->uvIndex = UVIndex::from($uvIndex);
        return $this;
    }

    /**
     * Get UV index.
     *
     * @return \Rugaard\WeatherKit\Enums\UVIndex
     */
    public function getUVIndex(): UVIndex
    {
        return $this->uvIndex;
    }

    /**
     * Set visibility (in meters).
     *
     * @param int|float $visibility
     * @return $this
     */
    public function setVisibility(int|float $visibility): self
    {
        $this->visibility = new Visibility(['value' => $visibility]);
        return $this;
    }

    /**
     * Get visibility.
     *
     * @return \Rugaard\WeatherKit\DTO\Measurements\Visibility
     */
    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    /**
     * Set wind measurement.
     *
     * @return $this
     */
    public function setWind(int|float $speed, int|float|null $gust, ?int $direction): self
    {
        $this->wind = Collection::make([
            'speed' => new Wind(['value' => $speed]),
            'gust' => $gust !== null ? new Wind(['value' => $gust]) : null,
            'direction' => $direction !== null ? new Direction(['value' => $direction]) : null
        ]);
        return $this;
    }

    /**
     * Get wind measurements.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getWind(): Collection
    {
        return $this->wind;
    }
}
