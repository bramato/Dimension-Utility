<?php

declare(strict_types=1);

namespace Bramato\DimensionUtility\Domain\Dto\Traits;

use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;
use Bramato\DimensionUtility\Services\WeightConversionService;
use InvalidArgumentException;

trait PhysicalObjectTrait
{
    /**
     * Calculates the density of the object (mass / volume).
     * Requires the object to have 'weight' (WeightDto) and 'dimensions' (BoxDto) properties.
     *
     * @return float The density in kilograms per cubic meter (kg/m³).
     * @throws \LogicException if weight or dimensions properties are missing or not the correct type.
     * @throws InvalidArgumentException if unit conversion fails.
     */
    public function calculateDensity(): float
    {
        if (!property_exists($this, 'weight') || !$this->weight instanceof WeightDto) {
            throw new \LogicException('Object must have a WeightDto property named "weight" to calculate density.');
        }
        if (!property_exists($this, 'dimensions') || !$this->dimensions instanceof BoxDto) {
            throw new \LogicException('Object must have a BoxDto property named "dimensions" to calculate density.');
        }

        // Convert weight to KG
        $weightInKg = WeightConversionService::create($this->weight)->convert(WeightEnum::KILOGRAM);

        // Calculate volume in cubic meters (already handled by BoxDto)
        $volumeInCubicMeters = $this->dimensions->calculateVolume(); // Assumes calculateVolume returns m³

        if ($volumeInCubicMeters <= 0) {
            // Avoid division by zero or negative volume
            throw new \LogicException('Volume must be positive to calculate density.');
        }

        return $weightInKg->value / $volumeInCubicMeters;
    }

    // Potential future methods:
    // public function getVolume(?DimensionEnum $lengthUnit = null): float { ... }
    // public function getSurfaceArea(?DimensionEnum $lengthUnit = null): float { ... }
}
