<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\WeightConversionService;
use InvalidArgumentException;

/**
 * Represents a product packaged for shipping.
 */
class ProductPackageDto
{
    /**
     * Creates a new ProductPackageDto instance.
     *
     * @param ProductDto $product The product contained in the package.
     * @param BoxDto $packageDimensions The external dimensions of the package.
     * @param WeightDto $totalWeight The total weight including product and packaging.
     * @param WeightDto|null $emptyPackageWeight Optional: The weight of the packaging material itself.
     */
    public function __construct(
        public readonly ProductDto $product,
        public readonly BoxDto $packageDimensions,
        public readonly WeightDto $totalWeight,
        public readonly ?WeightDto $emptyPackageWeight = null
    ) {}

    /**
     * Calculates the weight of the packaging material.
     *
     * Requires the totalWeight and the product's weight to be in compatible units or
     * convertible to a common unit (default: KILOGRAM).
     *
     * @param WeightEnum|null $unitToReturn Optional: The unit for the returned packaging weight.
     *                                        If null, uses the unit of totalWeight.
     * @return WeightDto|null The calculated weight of the packaging, or null if calculation is not possible.
     */
    public function calculatePackagingWeight(?WeightEnum $unitToReturn = null): ?WeightDto
    {
        try {
            // Convert total weight and product weight to KG for subtraction
            $totalWeightKg = WeightConversionService::create($this->totalWeight)->convert(WeightEnum::KILOGRAM);
            $productWeightKg = WeightConversionService::create($this->product->weight)->convert(WeightEnum::KILOGRAM);

            $packagingWeightKgValue = $totalWeightKg->value - $productWeightKg->value;

            if ($packagingWeightKgValue < 0) {
                // Handle potential inconsistency (e.g., log warning, return null)
                return null;
            }

            $packagingWeightKgDto = new WeightDto($packagingWeightKgValue, WeightEnum::KILOGRAM);

            // Convert to the desired return unit if specified
            $targetUnit = $unitToReturn ?? $this->totalWeight->unit;
            if ($targetUnit !== WeightEnum::KILOGRAM) {
                return WeightConversionService::create($packagingWeightKgDto)->convert($targetUnit);
            }

            return $packagingWeightKgDto;
        } catch (InvalidArgumentException $e) {
            // Handle cases where units are fundamentally incompatible (shouldn't happen with enums)
            // Or if conversion service fails.
            // Log error $e->getMessage();
            return null;
        }
    }

    /**
     * Gets the weight of the packaging, either directly if provided or by calculation.
     *
     * @param WeightEnum|null $unitToReturn Optional: The unit for the returned packaging weight.
     * @return WeightDto|null
     */
    public function getPackagingWeight(?WeightEnum $unitToReturn = null): ?WeightDto
    {
        if ($this->emptyPackageWeight !== null) {
            $targetUnit = $unitToReturn ?? $this->emptyPackageWeight->unit;
            if ($this->emptyPackageWeight->unit !== $targetUnit) {
                try {
                    return WeightConversionService::create($this->emptyPackageWeight)->convert($targetUnit);
                } catch (InvalidArgumentException $e) {
                    // Log error $e->getMessage();
                    return null; // Return null if conversion fails
                }
            }
            return $this->emptyPackageWeight;
        }

        return $this->calculatePackagingWeight($unitToReturn);
    }
}
