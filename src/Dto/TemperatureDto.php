<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\TemperatureEnum;
use Bramato\DimensionUtility\Services\TemperatureConversionService;

/**
 * Represents a temperature measurement value and its unit.
 */
class TemperatureDto
{
    /**
     * Creates a new TemperatureDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param TemperatureEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public TemperatureEnum $unit
    ) {}

    /**
     * Static factory method to create a TemperatureDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'CELSIUS').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in TemperatureEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, TemperatureEnum::from($unit));
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
     * Converts the measurement to Celsius.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toC(): TemperatureDto
    {
        return TemperatureConversionService::create($this)->convert(TemperatureEnum::CELSIUS);
    }

    /**
     * Converts the measurement to Fahrenheit.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toF(): TemperatureDto
    {
        return TemperatureConversionService::create($this)->convert(TemperatureEnum::FAHRENHEIT);
    }

    /**
     * Converts the measurement to Kelvin.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toK(): TemperatureDto
    {
        return TemperatureConversionService::create($this)->convert(TemperatureEnum::KELVIN);
    }
}
