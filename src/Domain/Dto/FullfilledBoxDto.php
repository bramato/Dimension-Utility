<?php

declare(strict_types=1);

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Domain\Dto\ProductDto;

/**
 * Represents a box with specific dimensions and a total weight (box + contents).
 */
class FullfilledBoxDto
{
    /**
     * Creates a new FullfilledBoxDto instance.
     *
     * @param BoxDto $dimensions The external dimensions of the box.
     * @param WeightDto $totalWeight The total weight of the box including its contents.
     * @param ProductDto[] $items The items contained within the box.
     */
    public function __construct(
        public readonly BoxDto $dimensions,
        public readonly WeightDto $totalWeight,
        /** @var ProductDto[] */
        public readonly array $items = []
    ) {}

    /**
     * Static factory method to create a FullfilledBoxDto using metric units.
     *
     * @param float $lengthCm Length in Centimeters.
     * @param float $widthCm Width in Centimeters.
     * @param float $heightCm Height in Centimeters.
     * @param float $weightKg Total weight in Kilograms.
     * @return static
     */
    public static function createMetric(
        float $lengthCm,
        float $widthCm,
        float $heightCm,
        float $weightKg
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($lengthCm, DimensionEnum::CENTIMETER),
            new DimensionDto($widthCm, DimensionEnum::CENTIMETER),
            new DimensionDto($heightCm, DimensionEnum::CENTIMETER)
        );
        $weight = new WeightDto($weightKg, WeightEnum::KILOGRAM);

        return new self($dimensions, $weight);
    }

    /**
     * Static factory method to create a FullfilledBoxDto using imperial units.
     *
     * @param float $lengthInch Length in Inches.
     * @param float $widthInch Width in Inches.
     * @param float $heightInch Height in Inches.
     * @param float $weightPound Total weight in Pounds.
     * @return static
     */
    public static function createImperial(
        float $lengthInch,
        float $widthInch,
        float $heightInch,
        float $weightPound
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($lengthInch, DimensionEnum::INCH),
            new DimensionDto($widthInch, DimensionEnum::INCH),
            new DimensionDto($heightInch, DimensionEnum::INCH)
        );
        $weight = new WeightDto($weightPound, WeightEnum::POUND);

        return new self($dimensions, $weight);
    }

    // Potential future methods:
    // - getDimensionsIn(DimensionEnum $unit): BoxDto
    // - getWeightIn(WeightEnum $unit): WeightDto
    // - calculateDensity(): float (if we assume uniform density)
}
