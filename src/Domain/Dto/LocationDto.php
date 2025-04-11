<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\DimensionDto;

/**
 * Represents geographical coordinates (latitude, longitude) and optional altitude.
 */
class LocationDto
{
    /**
     * Creates a new LocationDto instance.
     *
     * @param float $latitude Latitude in decimal degrees.
     * @param float $longitude Longitude in decimal degrees.
     * @param DimensionDto|null $altitude Optional: Altitude/elevation above sea level.
     */
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly ?DimensionDto $altitude = null
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException('Latitude must be between -90 and 90 degrees.');
        }
        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException('Longitude must be between -180 and 180 degrees.');
        }
    }

    /**
     * Returns a string representation of the coordinates.
     *
     * @return string
     */
    public function __toString(): string
    {
        $str = "Lat: {$this->latitude}, Lon: {$this->longitude}";
        if ($this->altitude !== null) {
            $str .= ", Alt: " . (string)$this->altitude;
        }
        return $str;
    }
}
