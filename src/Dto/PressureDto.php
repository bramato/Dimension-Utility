<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\PressureEnum;
use Bramato\DimensionUtility\Services\PressureConversionService;

/**
 * Represents a pressure measurement value and its unit.
 */
class PressureDto
{
    /**
     * Creates a new PressureDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param PressureEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public PressureEnum $unit
    ) {}

    /**
     * Static factory method to create a PressureDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'PASCAL').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in PressureEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, PressureEnum::from($unit));
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
     * Converts the measurement to Pascals (Pa).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toPa(): PressureDto
    {
        return PressureConversionService::create($this)->convert(PressureEnum::PASCAL);
    }

    /**
     * Converts the measurement to Kilopascals (kPa).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toKPa(): PressureDto
    {
        return PressureConversionService::create($this)->convert(PressureEnum::KILOPASCAL);
    }

    /**
     * Converts the measurement to Bar.
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toBar(): PressureDto
    {
        return PressureConversionService::create($this)->convert(PressureEnum::BAR);
    }

    /**
     * Converts the measurement to Pounds per Square Inch (psi).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toPsi(): PressureDto
    {
        return PressureConversionService::create($this)->convert(PressureEnum::PSI);
    }
}
