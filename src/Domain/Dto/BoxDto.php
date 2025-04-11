<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;
use InvalidArgumentException;

/**
 * Represents the dimensions (length, width, height) of a box-like object,
 * including its internal dimensions and empty weight.
 */
class BoxDto
{
    public readonly DimensionDto $innerLength;
    public readonly DimensionDto $innerWidth;
    public readonly DimensionDto $innerHeight;
    public readonly WeightDto $weight;

    /**
     * Creates a new BoxDto instance.
     *
     * @param DimensionDto $length External length of the box.
     * @param DimensionDto $width External width of the box.
     * @param DimensionDto $height External height of the box.
     * @param WeightDto|null $weight Weight of the empty box. If null, it's calculated based on surface area.
     * @param DimensionDto|null $innerLength Internal length. If null, calculated as external length - 1cm.
     * @param DimensionDto|null $innerWidth Internal width. If null, calculated as external width - 1cm.
     * @param DimensionDto|null $innerHeight Internal height. If null, calculated as external height - 1cm.
     * @param bool $is_empty Indicates if the box is considered empty (defaults to true).
     * @throws InvalidArgumentException If provided inner dimensions are not smaller than outer dimensions.
     */
    public function __construct(
        public readonly DimensionDto $length,
        public readonly DimensionDto $width,
        public readonly DimensionDto $height,
        ?WeightDto $weight = null,
        ?DimensionDto $innerLength = null,
        ?DimensionDto $innerWidth = null,
        ?DimensionDto $innerHeight = null,
        public readonly bool $is_empty = true
    ) {
        $cmUnit = DimensionEnum::CENTIMETER;

        // --- Calculate Weight if Null --- 
        if ($weight === null) {
            $surfaceAreaM2 = $this->calculateSurfaceArea(DimensionEnum::METER);
            $calculatedWeightKg = $surfaceAreaM2 * 0.6; // Factor: 0.6 kg/m²
            $this->weight = new WeightDto($calculatedWeightKg, WeightEnum::KILOGRAM);
        } else {
            $this->weight = $weight;
        }

        // --- Calculate or Validate Inner Dimensions --- 

        // Length
        $outerLengthCm = DimensionConversionService::create($this->length)->convert($cmUnit)->value;
        if ($innerLength === null) {
            $calculatedInnerLengthCm = max(0, $outerLengthCm - 1); // Ensure non-negative
            $this->innerLength = new DimensionDto($calculatedInnerLengthCm, $cmUnit);
        } else {
            $innerLengthCm = DimensionConversionService::create($innerLength)->convert($cmUnit)->value;
            if ($innerLengthCm >= $outerLengthCm) {
                throw new InvalidArgumentException('Inner length must be smaller than outer length.');
            }
            $this->innerLength = $innerLength;
        }

        // Width
        $outerWidthCm = DimensionConversionService::create($this->width)->convert($cmUnit)->value;
        if ($innerWidth === null) {
            $calculatedInnerWidthCm = max(0, $outerWidthCm - 1);
            $this->innerWidth = new DimensionDto($calculatedInnerWidthCm, $cmUnit);
        } else {
            $innerWidthCm = DimensionConversionService::create($innerWidth)->convert($cmUnit)->value;
            if ($innerWidthCm >= $outerWidthCm) {
                throw new InvalidArgumentException('Inner width must be smaller than outer width.');
            }
            $this->innerWidth = $innerWidth;
        }

        // Height
        $outerHeightCm = DimensionConversionService::create($this->height)->convert($cmUnit)->value;
        if ($innerHeight === null) {
            $calculatedInnerHeightCm = max(0, $outerHeightCm - 1);
            $this->innerHeight = new DimensionDto($calculatedInnerHeightCm, $cmUnit);
        } else {
            $innerHeightCm = DimensionConversionService::create($innerHeight)->convert($cmUnit)->value;
            if ($innerHeightCm >= $outerHeightCm) {
                throw new InvalidArgumentException('Inner height must be smaller than outer height.');
            }
            $this->innerHeight = $innerHeight;
        }
    }

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
