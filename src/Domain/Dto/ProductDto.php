<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Dto\LiquidVolumeDto;
use Bramato\DimensionUtility\Domain\Dto\Traits\PhysicalObjectTrait;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Dto\DimensionDto;

/**
 * Represents a physical product with its basic attributes.
 */
class ProductDto
{
    use PhysicalObjectTrait;

    /**
     * Creates a new ProductDto instance.
     *
     * @param string $sku Stock Keeping Unit or product identifier.
     * @param string $name Name of the product.
     * @param BoxDto $dimensions Physical dimensions of the product.
     * @param WeightDto $weight Weight of the product.
     * @param LiquidVolumeDto|null $liquidVolume Optional: Liquid volume if applicable (e.g., for bottles).
     */
    public function __construct(
        public readonly string $sku,
        public readonly string $name,
        public readonly BoxDto $dimensions,
        public readonly WeightDto $weight,
        public readonly ?LiquidVolumeDto $liquidVolume = null
    ) {}

    /**
     * Static factory method to create a ProductDto using metric units.
     *
     * @param string $sku
     * @param string $name
     * @param float $lengthCm Length in Centimeters.
     * @param float $widthCm Width in Centimeters.
     * @param float $heightCm Height in Centimeters.
     * @param float $weightKg Weight in Kilograms.
     * @param LiquidVolumeDto|null $liquidVolume Optional liquid volume DTO.
     * @return static
     */
    public static function createMetric(
        string $sku,
        string $name,
        float $lengthCm,
        float $widthCm,
        float $heightCm,
        float $weightKg,
        ?LiquidVolumeDto $liquidVolume = null
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($lengthCm, DimensionEnum::CENTIMETER),
            new DimensionDto($widthCm, DimensionEnum::CENTIMETER),
            new DimensionDto($heightCm, DimensionEnum::CENTIMETER)
        );
        $weight = new WeightDto($weightKg, WeightEnum::KILOGRAM);

        return new self($sku, $name, $dimensions, $weight, $liquidVolume);
    }

    /**
     * Static factory method to create a ProductDto using imperial units.
     *
     * @param string $sku
     * @param string $name
     * @param float $lengthInch Length in Inches.
     * @param float $widthInch Width in Inches.
     * @param float $heightInch Height in Inches.
     * @param float $weightPound Weight in Pounds.
     * @param LiquidVolumeDto|null $liquidVolume Optional liquid volume DTO.
     * @return static
     */
    public static function createImperial(
        string $sku,
        string $name,
        float $lengthInch,
        float $widthInch,
        float $heightInch,
        float $weightPound,
        ?LiquidVolumeDto $liquidVolume = null
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($lengthInch, DimensionEnum::INCH),
            new DimensionDto($widthInch, DimensionEnum::INCH),
            new DimensionDto($heightInch, DimensionEnum::INCH)
        );
        $weight = new WeightDto($weightPound, WeightEnum::POUND);

        return new self($sku, $name, $dimensions, $weight, $liquidVolume);
    }

    // Potential future methods:
    // - getWeightIn(WeightEnum $unit): WeightDto
    // - getDimensionsIn(DimensionEnum $unit): BoxDto (would need BoxDto method)
    // - getLiquidVolumeIn(LiquidVolumeEnum $unit): ?LiquidVolumeDto
}
