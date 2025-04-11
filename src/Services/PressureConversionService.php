<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\PressureDto;
use Bramato\DimensionUtility\Enum\PressureEnum;
use InvalidArgumentException;

/**
 * Service for converting pressure measurements between different units.
 */
class PressureConversionService
{
    // Conversion factors FROM PASCAL (Pa) to other units
    private const FACTORS_FROM_PASCAL = [
        PressureEnum::PASCAL->value => 1,
        PressureEnum::KILOPASCAL->value => 0.001,             // 1 Pa = 0.001 kPa
        PressureEnum::MEGAPASCAL->value => 0.000001,          // 1 Pa = 1e-6 MPa
        PressureEnum::BAR->value => 0.00001,             // 1 Pa = 1e-5 bar
        PressureEnum::MILLIBAR->value => 0.01,                // 1 Pa = 0.01 mbar
        PressureEnum::PSI->value => 0.0001450377,      // 1 Pa ≈ 0.000145 psi
        PressureEnum::ATMOSPHERE->value => 9.86923e-6,        // 1 Pa ≈ 9.869e-6 atm
        PressureEnum::TORR->value => 0.0075006168,      // 1 Pa ≈ 0.0075 Torr (mmHg)
    ];

    public PressureDto $pressureDto;

    public function __construct(PressureDto $pressureDto)
    {
        $this->pressureDto = $pressureDto;
    }

    public static function create(PressureDto $pressureDto): self
    {
        return new self($pressureDto);
    }

    /**
     * Converts the pressure measurement to the target unit.
     *
     * @param PressureEnum $targetUnit The unit to convert to.
     * @return PressureDto A new DTO instance with the converted value and target unit.
     * @throws InvalidArgumentException if the conversion units are not supported.
     */
    public function convert(PressureEnum $targetUnit): PressureDto
    {
        $factor = $this->getConversionFactor($this->pressureDto->unit, $targetUnit);
        $convertedValue = $this->pressureDto->value * $factor;
        return new PressureDto($convertedValue, $targetUnit);
    }

    /**
     * Calculates the conversion factor between two pressure units via PASCAL.
     */
    private function getConversionFactor(PressureEnum $fromUnit, PressureEnum $toUnit): float
    {
        if ($fromUnit === $toUnit) {
            return 1.0;
        }

        $factorFromBase = self::FACTORS_FROM_PASCAL[$fromUnit->value] ?? null;
        if ($factorFromBase === null) {
            throw new InvalidArgumentException("Base conversion factor not found for pressure unit: {$fromUnit->value}");
        }
        $toBaseFactor = 1 / $factorFromBase;

        $fromBaseToTargetFactor = self::FACTORS_FROM_PASCAL[$toUnit->value] ?? null;
        if ($fromBaseToTargetFactor === null) {
            throw new InvalidArgumentException("Target conversion factor not found for pressure unit: {$toUnit->value}");
        }

        return $toBaseFactor * $fromBaseToTargetFactor;
    }
}
