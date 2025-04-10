<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\LiquidVolumeEnum;
use Bramato\DimensionUtility\Services\LiquidVolumeConversionService;

/**
 * Represents a liquid volume measurement value and its unit.
 */
class LiquidVolumeDto
{
    /**
     * Creates a new LiquidVolumeDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param LiquidVolumeEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public LiquidVolumeEnum $unit
    ) {}

    /**
     * Static factory method to create a LiquidVolumeDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'L').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in LiquidVolumeEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, LiquidVolumeEnum::from($unit));
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
     * Converts the measurement to Liters.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toLT(): LiquidVolumeDto
    {
        return LiquidVolumeConversionService::create($this)->convert(LiquidVolumeEnum::L);
    }

    /**
     * Converts the measurement to Milliliters.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toML(): LiquidVolumeDto
    {
        return LiquidVolumeConversionService::create($this)->convert(LiquidVolumeEnum::ML);
    }

    /**
     * Converts the measurement to Gallons.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toGAL(): LiquidVolumeDto
    {
        return LiquidVolumeConversionService::create($this)->convert(LiquidVolumeEnum::GAL);
    }
}
