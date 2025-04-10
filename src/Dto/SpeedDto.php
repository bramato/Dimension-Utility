<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\SpeedEnum;
use Bramato\DimensionUtility\Services\SpeedConversionService;

/**
 * Represents a speed measurement value and its unit.
 */
class SpeedDto
{
    /**
     * Creates a new SpeedDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param SpeedEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public SpeedEnum $unit
    ) {}

    /**
     * Static factory method to create a SpeedDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'METER_PER_SECOND').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in SpeedEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, SpeedEnum::from($unit));
    }

    /**
     * Returns a string representation of the measurement (value and unit).
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value . ' ' . $this->unit->value;
    }

    /**
     * Converts the measurement to Meters per Second (m/s).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toMPS(): SpeedDto
    {
        return SpeedConversionService::create($this)->convert(SpeedEnum::METER_PER_SECOND);
    }

    /**
     * Converts the measurement to Kilometers per Hour (km/h).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toKPH(): SpeedDto
    {
        return SpeedConversionService::create($this)->convert(SpeedEnum::KILOMETER_PER_HOUR);
    }

    /**
     * Converts the measurement to Miles per Hour (mph).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toMPH(): SpeedDto
    {
        return SpeedConversionService::create($this)->convert(SpeedEnum::MILE_PER_HOUR);
    }
}
