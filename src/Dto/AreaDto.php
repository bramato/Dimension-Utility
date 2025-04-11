<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\AreaEnum;
use Bramato\DimensionUtility\Services\AreaConversionService;

/**
 * Represents an area measurement value and its unit.
 */
class AreaDto
{
    /**
     * Creates a new AreaDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param AreaEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public AreaEnum $unit
    ) {}

    /**
     * Static factory method to create an AreaDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'SQ_METER').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in AreaEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, AreaEnum::from($unit));
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
     * Converts the measurement to Square Meters.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toSQM(): AreaDto
    {
        // We need to create AreaConversionService first for this to work
        // For now, let's just return the call structure
        return AreaConversionService::create($this)->convert(AreaEnum::SQ_METER);
    }

    /**
     * Converts the measurement to Acres.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toACRE(): AreaDto
    {
        return AreaConversionService::create($this)->convert(AreaEnum::ACRE);
    }

    /**
     * Converts the measurement to Hectares.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toHECTARE(): AreaDto
    {
        return AreaConversionService::create($this)->convert(AreaEnum::HECTARE);
    }
}
