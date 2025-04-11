<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\TemperatureDto;
use Bramato\DimensionUtility\Dto\PressureDto;
use Bramato\DimensionUtility\Dto\SpeedDto;

/**
 * Represents a weather reading at a specific location and time.
 */
class WeatherReadingDto
{
    /**
     * Creates a new WeatherReadingDto instance.
     *
     * @param \DateTimeInterface $timestamp Time of the reading.
     * @param LocationDto $location Location of the reading.
     * @param TemperatureDto $temperature Air temperature.
     * @param PressureDto $pressure Atmospheric pressure.
     * @param float $humidity Relative humidity (e.g., 0.0 to 1.0 or 0 to 100).
     * @param SpeedDto|null $windSpeed Optional: Wind speed.
     * @param float|null $windDirection Optional: Wind direction in degrees (0-360).
     */
    public function __construct(
        public readonly \DateTimeInterface $timestamp,
        public readonly LocationDto $location,
        public readonly TemperatureDto $temperature,
        public readonly PressureDto $pressure,
        public readonly float $humidity,
        public readonly ?SpeedDto $windSpeed = null,
        public readonly ?float $windDirection = null
    ) {
        if ($humidity < 0) {
            // Basic validation, could be more complex (e.g., max 100 if percentage)
            throw new \InvalidArgumentException('Humidity cannot be negative.');
        }
        if ($windDirection !== null && ($windDirection < 0 || $windDirection > 360)) {
            throw new \InvalidArgumentException('Wind direction must be between 0 and 360 degrees.');
        }
    }
}
