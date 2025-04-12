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
     * @param int|null $reference_id Optional reference ID.
     * @param string|null $reference_type Optional reference type.
     */
    public function __construct(
        public readonly string $sku,
        public readonly string $name,
        public readonly BoxDto $dimensions,
        public readonly WeightDto $weight,
        public readonly ?LiquidVolumeDto $liquidVolume = null,
        public readonly ?int $reference_id = null,
        public readonly ?string $reference_type = null
    ) {}


    public static function create(
        string $sku,
        string $name,
        DimensionEnum $unitDimension,
        WeightEnum $unitWeight,
        float $length,
        float $width,
        float $height,
        float $weight,
        ?int $reference_id = null,
        ?string $reference_type = null
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($length, $unitDimension),
            new DimensionDto($width, $unitDimension),
            new DimensionDto($height, $unitDimension)
        );
        $weightDto = new WeightDto($weight, $unitWeight);
        return new self($sku, $name, $dimensions, $weightDto, null, $reference_id, $reference_type);
    }

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
     * @param int|null $reference_id Optional reference ID.
     * @param string|null $reference_type Optional reference type.
     * @return static
     */
    public static function createMetric(
        string $sku,
        string $name,
        float $lengthCm,
        float $widthCm,
        float $heightCm,
        float $weightKg,
        ?LiquidVolumeDto $liquidVolume = null,
        ?int $reference_id = null,
        ?string $reference_type = null
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($lengthCm, DimensionEnum::CENTIMETER),
            new DimensionDto($widthCm, DimensionEnum::CENTIMETER),
            new DimensionDto($heightCm, DimensionEnum::CENTIMETER)
        );
        $weight = new WeightDto($weightKg, WeightEnum::KILOGRAM);

        return new self($sku, $name, $dimensions, $weight, $liquidVolume, $reference_id, $reference_type);
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
     * @param int|null $reference_id Optional reference ID.
     * @param string|null $reference_type Optional reference type.
     * @return static
     */
    public static function createImperial(
        string $sku,
        string $name,
        float $lengthInch,
        float $widthInch,
        float $heightInch,
        float $weightPound,
        ?LiquidVolumeDto $liquidVolume = null,
        ?int $reference_id = null,
        ?string $reference_type = null
    ): self {
        $dimensions = new BoxDto(
            new DimensionDto($lengthInch, DimensionEnum::INCH),
            new DimensionDto($widthInch, DimensionEnum::INCH),
            new DimensionDto($heightInch, DimensionEnum::INCH)
        );
        $weight = new WeightDto($weightPound, WeightEnum::POUND);

        return new self($sku, $name, $dimensions, $weight, $liquidVolume, $reference_id, $reference_type);
    }


    public function getWeightInG(): WeightDto
    {
        return $this->weight->toG();
    }

    public function getDimensionsInCM(): BoxDto
    {
        $widthInCm = $this->dimensions->width->toCM();
        $heightInCm = $this->dimensions->height->toCM();
        $lengthInCm = $this->dimensions->length->toCM();
        return new BoxDto(
            $widthInCm,
            $heightInCm,
            $lengthInCm
        );
    }
    // Potential future methods:
    // - getWeightIn(WeightEnum $unit): WeightDto
    // - getDimensionsIn(DimensionEnum $unit): BoxDto (would need BoxDto method)
    // - getLiquidVolumeIn(LiquidVolumeEnum $unit): ?LiquidVolumeDto
}
