<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\WeightConversionService;

/**
 * Represents a weight measurement value and its unit.
 */
class WeightDto
{
    /**
     * Creates a new WeightDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param WeightEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public WeightEnum $unit
    ) {}

    /**
     * Static factory method to create a WeightDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'KILOGRAM').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in WeightEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, WeightEnum::from($unit));
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
     * Converts the measurement to Kilograms.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toKG(): WeightDto
    {
        return WeightConversionService::create($this)->convert(WeightEnum::KILOGRAM);
    }

    /**
     * Converts the measurement to Grams.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toG(): WeightDto
    {
        return WeightConversionService::create($this)->convert(WeightEnum::GRAM);
    }

    /**
     * Converts the measurement to Pounds.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toPOUND(): WeightDto
    {
        return WeightConversionService::create($this)->convert(WeightEnum::POUND);
    }
}
