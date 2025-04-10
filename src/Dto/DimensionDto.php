<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;

/**
 * Represents a dimension measurement value and its unit.
 */
class DimensionDto
{
    /**
     * Creates a new DimensionDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param DimensionEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public DimensionEnum $unit
    ) {}

    /**
     * Static factory method to create a DimensionDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'METER').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in DimensionEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, DimensionEnum::from($unit));
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
     * Converts the measurement to Centimeters.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toCM(): DimensionDto
    {
        return DimensionConversionService::create($this)->convert(DimensionEnum::CENTIMETER);
    }

    /**
     * Converts the measurement to Meters.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toM(): DimensionDto
    {
        return DimensionConversionService::create($this)->convert(DimensionEnum::METER);
    }

    /**
     * Converts the measurement to Inches.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toINCH(): DimensionDto
    {
        return DimensionConversionService::create($this)->convert(DimensionEnum::INCH);
    }
}
