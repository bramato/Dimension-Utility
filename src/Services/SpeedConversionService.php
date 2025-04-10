<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\SpeedDto;
use Bramato\DimensionUtility\Enum\SpeedEnum;
use InvalidArgumentException;

/**
 * Service for converting speed measurements between different units.
 */
class SpeedConversionService
{
    // Conversion factors FROM METER_PER_SECOND to other units
    private const FACTORS_FROM_MPS = [
        SpeedEnum::METER_PER_SECOND->value => 1,
        SpeedEnum::KILOMETER_PER_HOUR->value => 3.6,           // 1 m/s = 3.6 km/h
        SpeedEnum::MILE_PER_HOUR->value => 2.236936,      // 1 m/s ≈ 2.237 mph
        SpeedEnum::KNOT->value => 1.943844,          // 1 m/s ≈ 1.944 knots
        SpeedEnum::FOOT_PER_SECOND->value => 3.28084,       // 1 m/s ≈ 3.281 ft/s
    ];

    public SpeedDto $speedDto;

    public function __construct(SpeedDto $speedDto)
    {
        $this->speedDto = $speedDto;
    }

    public static function create(SpeedDto $speedDto): self
    {
        return new self($speedDto);
    }

    /**
     * Converts the speed measurement to the target unit.
     *
     * @param SpeedEnum $targetUnit The unit to convert to.
     * @return SpeedDto A new DTO instance with the converted value and target unit.
     * @throws InvalidArgumentException if the conversion units are not supported.
     */
    public function convert(SpeedEnum $targetUnit): SpeedDto
    {
        $factor = $this->getConversionFactor($this->speedDto->unit, $targetUnit);
        $convertedValue = $this->speedDto->value * $factor;
        return new SpeedDto($convertedValue, $targetUnit);
    }

    /**
     * Calculates the conversion factor between two speed units via MPS.
     */
    private function getConversionFactor(SpeedEnum $fromUnit, SpeedEnum $toUnit): float
    {
        if ($fromUnit === $toUnit) {
            return 1.0;
        }

        $factorFromBase = self::FACTORS_FROM_MPS[$fromUnit->value] ?? null;
        if ($factorFromBase === null) {
            throw new InvalidArgumentException("Base conversion factor not found for speed unit: {$fromUnit->value}");
        }
        $toBaseFactor = 1 / $factorFromBase;

        $fromBaseToTargetFactor = self::FACTORS_FROM_MPS[$toUnit->value] ?? null;
        if ($fromBaseToTargetFactor === null) {
            throw new InvalidArgumentException("Target conversion factor not found for speed unit: {$toUnit->value}");
        }

        return $toBaseFactor * $fromBaseToTargetFactor;
    }
}
