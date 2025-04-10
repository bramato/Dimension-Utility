<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\LiquidVolumeDto;
use Bramato\DimensionUtility\Enum\LiquidVolumeEnum;
use InvalidArgumentException;

class LiquidVolumeConversionService
{
    // Conversion table between all units (from unit to other units)
    private const CONVERSION_TABLE = [
        LiquidVolumeEnum::ML->value => [
            LiquidVolumeEnum::ML->value => 1,
            LiquidVolumeEnum::L->value => 0.001,
            LiquidVolumeEnum::FL_OZ->value => 0.033814, // 1 / 29.5735
            LiquidVolumeEnum::GAL->value => 0.000264172, // 1 / 3785.41
            LiquidVolumeEnum::PT->value => 0.00211338, // 1 / 473.176
            LiquidVolumeEnum::QT->value => 0.00105669, // 1 / 946.353
            LiquidVolumeEnum::C->value => 0.00422675, // 1 / 236.588
        ],
        LiquidVolumeEnum::L->value => [
            LiquidVolumeEnum::ML->value => 1000,
            LiquidVolumeEnum::L->value => 1,
            LiquidVolumeEnum::FL_OZ->value => 33.814, // 1000 * 0.033814
            LiquidVolumeEnum::GAL->value => 0.264172, // 1000 * 0.000264172
            LiquidVolumeEnum::PT->value => 2.11338, // 1000 * 0.00211338
            LiquidVolumeEnum::QT->value => 1.05669, // 1000 * 0.00105669
            LiquidVolumeEnum::C->value => 4.22675, // 1000 * 0.00422675
        ],
        LiquidVolumeEnum::FL_OZ->value => [
            LiquidVolumeEnum::ML->value => 29.5735,
            LiquidVolumeEnum::L->value => 0.0295735,
            LiquidVolumeEnum::FL_OZ->value => 1,
            LiquidVolumeEnum::GAL->value => 0.0078125, // 1 / 128
            LiquidVolumeEnum::PT->value => 0.0625, // 1 / 16
            LiquidVolumeEnum::QT->value => 0.03125, // 1 / 32
            LiquidVolumeEnum::C->value => 0.125, // 1 / 8
        ],
        LiquidVolumeEnum::GAL->value => [
            LiquidVolumeEnum::ML->value => 3785.41,
            LiquidVolumeEnum::L->value => 3.78541,
            LiquidVolumeEnum::FL_OZ->value => 128,
            LiquidVolumeEnum::GAL->value => 1,
            LiquidVolumeEnum::PT->value => 8,
            LiquidVolumeEnum::QT->value => 4,
            LiquidVolumeEnum::C->value => 16,
        ],
        LiquidVolumeEnum::PT->value => [
            LiquidVolumeEnum::ML->value => 473.176,
            LiquidVolumeEnum::L->value => 0.473176,
            LiquidVolumeEnum::FL_OZ->value => 16,
            LiquidVolumeEnum::GAL->value => 0.125, // 1 / 8
            LiquidVolumeEnum::PT->value => 1,
            LiquidVolumeEnum::QT->value => 0.5,
            LiquidVolumeEnum::C->value => 2,
        ],
        LiquidVolumeEnum::QT->value => [
            LiquidVolumeEnum::ML->value => 946.353,
            LiquidVolumeEnum::L->value => 0.946353,
            LiquidVolumeEnum::FL_OZ->value => 32,
            LiquidVolumeEnum::GAL->value => 0.25, // 1 / 4
            LiquidVolumeEnum::PT->value => 2,
            LiquidVolumeEnum::QT->value => 1,
            LiquidVolumeEnum::C->value => 4,
        ],
        LiquidVolumeEnum::C->value => [
            LiquidVolumeEnum::ML->value => 236.588,
            LiquidVolumeEnum::L->value => 0.236588,
            LiquidVolumeEnum::FL_OZ->value => 8,
            LiquidVolumeEnum::GAL->value => 0.0625, // 1 / 16
            LiquidVolumeEnum::PT->value => 0.5,
            LiquidVolumeEnum::QT->value => 0.25,
            LiquidVolumeEnum::C->value => 1,
        ],
    ];
    public LiquidVolumeDto $liquidVolumeDto;
    public function __construct(LiquidVolumeDto $liquidVolumeDto)
    {
        $this->liquidVolumeDto = $liquidVolumeDto;
    }

    /**
     * Static method to create a LiquidVolumeDto with error handling.
     *
     * @param LiquidVolumeDto $liquidVolumeDto
     * @return LiquidVolumeConversionService The DTO created.
     */
    public static function create(LiquidVolumeDto $liquidVolumeDto): self
    {
        return new self($liquidVolumeDto);
    }

    /**
     * Convert a given LiquidVolumeDto to the desired unit.
     *
     * @param LiquidVolumeDto $dto The DTO containing the value and current unit.
     * @param LiquidVolumeEnum $targetUnit The target unit to convert to.
     * @return LiquidVolumeDto The DTO with the converted value.
     * @throws InvalidArgumentException if the unit is not supported.
     */
    public function convert(LiquidVolumeEnum $targetUnit): LiquidVolumeDto
    {
        // Retrieve the conversion factor from the current unit to the target unit
        $conversionFactor = $this->getConversionFactor($this->liquidVolumeDto->unit, $targetUnit);

        // Convert the value using the factor
        $convertedValue = $this->liquidVolumeDto->value * $conversionFactor;

        return new LiquidVolumeDto($convertedValue, $targetUnit);
    }

    /**
     * Get the conversion factor between two units.
     *
     * @param LiquidVolumeEnum $fromUnit The unit to convert from.
     * @param LiquidVolumeEnum $toUnit The unit to convert to.
     * @return float The conversion factor.
     * @throws InvalidArgumentException if the units are not supported.
     */
    private function getConversionFactor(LiquidVolumeEnum $fromUnit, LiquidVolumeEnum $toUnit): float
    {
        if (!isset(self::CONVERSION_TABLE[$fromUnit->value][$toUnit->value])) {
            throw new InvalidArgumentException("Conversion from {$fromUnit->value} to {$toUnit->value} is not supported.");
        }

        return self::CONVERSION_TABLE[$fromUnit->value][$toUnit->value];
    }
}
