<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\AreaDto;
use Bramato\DimensionUtility\Enum\AreaEnum;
use InvalidArgumentException;

/**
 * Service for converting area measurements between different units.
 */
class AreaConversionService
{
    // Conversion factors FROM SQ_METER to other units
    private const FACTORS_FROM_SQ_METER = [
        AreaEnum::SQ_METER->value => 1,
        AreaEnum::SQ_KILOMETER->value => 0.000001,      // 1 m² = 1e-6 km²
        AreaEnum::SQ_CENTIMETER->value => 10000,       // 1 m² = 10000 cm²
        AreaEnum::SQ_MILLIMETER->value => 1000000,     // 1 m² = 1e6 mm²
        AreaEnum::SQ_FOOT->value => 10.7639104167,  // 1 m² ≈ 10.764 sq ft
        AreaEnum::SQ_YARD->value => 1.1959900463,   // 1 m² ≈ 1.196 sq yd
        AreaEnum::SQ_INCH->value => 1550.00310001,  // 1 m² ≈ 1550 sq in
        AreaEnum::SQ_MILE->value => 3.861021585e-7, // 1 m² ≈ 3.861e-7 sq mi
        AreaEnum::ACRE->value => 0.00024710538,  // 1 m² ≈ 0.000247 acres
        AreaEnum::HECTARE->value => 0.0001,         // 1 m² = 0.0001 ha
    ];

    /**
     * The AreaDto instance to perform conversions on.
     */
    public AreaDto $areaDto;

    /**
     * Creates a new AreaConversionService instance.
     *
     * @param AreaDto $areaDto The DTO containing the initial value and unit.
     */
    public function __construct(AreaDto $areaDto)
    {
        $this->areaDto = $areaDto;
    }

    /**
     * Static factory method to create an AreaConversionService instance.
     *
     * @param AreaDto $areaDto The DTO containing the initial value and unit.
     * @return static
     */
    public static function create(AreaDto $areaDto): self
    {
        return new self($areaDto);
    }

    /**
     * Converts the area measurement to the target unit.
     *
     * @param AreaEnum $targetUnit The unit to convert to.
     * @return AreaDto A new DTO instance with the converted value and target unit.
     * @throws InvalidArgumentException if the conversion units are not supported.
     */
    public function convert(AreaEnum $targetUnit): AreaDto
    {
        $factor = $this->getConversionFactor($this->areaDto->unit, $targetUnit);
        $convertedValue = $this->areaDto->value * $factor;
        return new AreaDto($convertedValue, $targetUnit);
    }

    /**
     * Calculates the conversion factor between two area units.
     * Conversion is done via the base unit (SQ_METER).
     *
     * @param AreaEnum $fromUnit The unit to convert from.
     * @param AreaEnum $toUnit The unit to convert to.
     * @return float The conversion factor.
     * @throws InvalidArgumentException if units are not found in the conversion table.
     */
    private function getConversionFactor(AreaEnum $fromUnit, AreaEnum $toUnit): float
    {
        if ($fromUnit === $toUnit) {
            return 1.0;
        }

        // Factor to convert fromUnit to SQ_METER
        $factorFromBase = self::FACTORS_FROM_SQ_METER[$fromUnit->value] ?? null;
        if ($factorFromBase === null) {
            throw new InvalidArgumentException("Base conversion factor not found for unit: {$fromUnit->value}");
        }
        // Need the inverse factor: fromUnit -> SQ_METER means dividing by the factor (SQ_METER -> fromUnit)
        $toBaseFactor = 1 / $factorFromBase;

        // Factor to convert SQ_METER to toUnit
        $fromBaseToTargetFactor = self::FACTORS_FROM_SQ_METER[$toUnit->value] ?? null;
        if ($fromBaseToTargetFactor === null) {
            throw new InvalidArgumentException("Target conversion factor not found for unit: {$toUnit->value}");
        }

        // Combined factor: (value in fromUnit) * (1 / factor(SQM->fromUnit)) * factor(SQM->toUnit)
        return $toBaseFactor * $fromBaseToTargetFactor;
    }
}
