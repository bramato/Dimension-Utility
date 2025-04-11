<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;
use InvalidArgumentException;

/**
 * Represents the dimensions (length, width, height) of a box-like object.
 */
class BoxDto
{
    /**
     * Creates a new BoxDto instance.
     *
     * @param DimensionDto $length Length of the box.
     * @param DimensionDto $width Width of the box.
     * @param DimensionDto $height Height of the box.
     */
    public function __construct(
        public readonly DimensionDto $length,
        public readonly DimensionDto $width,
        public readonly DimensionDto $height
    ) {}

    /**
     * Calculates the volume of the box.
     *
     * Note: This calculation converts all dimensions to a base unit (e.g., METER)
     * before multiplying. The resulting volume is a raw float value in cubic meters.
     * For more advanced use cases, consider returning a dedicated VolumeDto.
     *
     * @param DimensionEnum $baseUnit The unit to use for internal calculation (default: METER).
     * @return float The calculated volume in cubic units of the base unit (e.g., cubic meters).
     * @throws InvalidArgumentException If conversion to the base unit fails for any dimension.
     */
    public function calculateVolume(DimensionEnum $baseUnit = DimensionEnum::METER): float
    {
        // Convert all dimensions to the base unit for consistent calculation
        $lengthInBase = DimensionConversionService::create($this->length)->convert($baseUnit);
        $widthInBase = DimensionConversionService::create($this->width)->convert($baseUnit);
        $heightInBase = DimensionConversionService::create($this->height)->convert($baseUnit);

        return $lengthInBase->value * $widthInBase->value * $heightInBase->value;
    }

    /**
     * Calculates the total surface area of the box.
     *
     * Note: This calculation converts dimensions to a base unit (e.g., METER)
     * for intermediate calculations and returns the result as a float
     * in square units of the base unit (e.g., square meters).
     * A future enhancement could return an AreaDto.
     *
     * @param DimensionEnum $baseUnit The unit to use for internal calculation (default: METER).
     * @return float The calculated surface area in square units of the base unit.
     * @throws InvalidArgumentException If conversion to the base unit fails for any dimension.
     */
    public function calculateSurfaceArea(DimensionEnum $baseUnit = DimensionEnum::METER): float
    {
        $l = DimensionConversionService::create($this->length)->convert($baseUnit)->value;
        $w = DimensionConversionService::create($this->width)->convert($baseUnit)->value;
        $h = DimensionConversionService::create($this->height)->convert($baseUnit)->value;

        return 2 * (($l * $w) + ($l * $h) + ($w * $h));
    }

    /**
     * Gets the longest dimension among length, width, and height.
     *
     * @param DimensionEnum|null $convertToUnit Optional: Convert the result to this unit.
     * @return DimensionDto The DimensionDto representing the longest dimension.
     */
    public function getMaxDimension(?DimensionEnum $convertToUnit = null): DimensionDto
    {
        $dimensions = [$this->length, $this->width, $this->height];
        $baseUnit = DimensionEnum::METER; // Use a consistent base for comparison

        $maxDim = $dimensions[0];
        $maxValueInBase = DimensionConversionService::create($maxDim)->convert($baseUnit)->value;

        for ($i = 1; $i < count($dimensions); $i++) {
            $currentValueInBase = DimensionConversionService::create($dimensions[$i])->convert($baseUnit)->value;
            if ($currentValueInBase > $maxValueInBase) {
                $maxValueInBase = $currentValueInBase;
                $maxDim = $dimensions[$i];
            }
        }

        if ($convertToUnit !== null && $maxDim->unit !== $convertToUnit) {
            return DimensionConversionService::create($maxDim)->convert($convertToUnit);
        }

        return $maxDim;
    }
}
