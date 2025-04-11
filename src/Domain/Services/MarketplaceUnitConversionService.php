<?php

declare(strict_types=1);

namespace Bramato\DimensionUtility\Domain\Services;

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use InvalidArgumentException;

/**
 * Service to convert measurement values from marketplace-specific units
 * into internal DimensionDto or WeightDto objects.
 */
class MarketplaceUnitConversionService
{
    /**
     * Base mapping from common unit strings to internal Enum values.
     * Used as a fallback or default.
     */
    private static array $baseUnitMappings = [
        // Dimensions
        'centimeters' => DimensionEnum::CENTIMETER->value,
        'inches'      => DimensionEnum::INCH->value,
        'meters'      => DimensionEnum::METER->value,
        'kilometers'  => DimensionEnum::KILOMETER->value,
        'feet'        => DimensionEnum::FOOT->value,
        'yards'       => DimensionEnum::YARD->value,
        'miles'       => DimensionEnum::MILE->value,
        'millimeters' => DimensionEnum::MILLIMETER->value,
        'micrometers' => DimensionEnum::MICROMETER->value,
        'nanometers'  => DimensionEnum::NANOMETER->value,
        'decimeters'  => DimensionEnum::DECIMETER->value,

        // Weights
        'kilograms'   => WeightEnum::KILOGRAM->value,
        'grams'       => WeightEnum::GRAM->value,
        'pounds'      => WeightEnum::POUND->value,
        'ounces'      => WeightEnum::OUNCE->value,
        'milligrams'  => WeightEnum::MILLIGRAM->value,
        'hundredths_pounds' => WeightEnum::HUNDREDTHS_POUND->value,
    ];

    /**
     * Mapping specifically for Amazon unit strings.
     * Initially a copy of base, customize as needed.
     */
    private static array $amazonUnitMappings = [
        // Dimensions
        'centimeters' => DimensionEnum::CENTIMETER->value,
        'inches'      => DimensionEnum::INCH->value,
        'meters'      => DimensionEnum::METER->value,
        'kilometers'  => DimensionEnum::KILOMETER->value,
        'feet'        => DimensionEnum::FOOT->value,
        'yards'       => DimensionEnum::YARD->value,
        'miles'       => DimensionEnum::MILE->value,
        'millimeters' => DimensionEnum::MILLIMETER->value,
        'micrometers' => DimensionEnum::MICROMETER->value,
        'nanometers'  => DimensionEnum::NANOMETER->value,
        'decimeters'  => DimensionEnum::DECIMETER->value,

        // Weights
        'kilograms'   => WeightEnum::KILOGRAM->value,
        'grams'       => WeightEnum::GRAM->value,
        'pounds'      => WeightEnum::POUND->value,
        'ounces'      => WeightEnum::OUNCE->value,
        'milligrams'  => WeightEnum::MILLIGRAM->value,
        'hundredths_pounds' => WeightEnum::HUNDREDTHS_POUND->value,
    ];

    /**
     * Selects the appropriate unit mapping array based on the marketplace identifier.
     *
     * @param string $marketplace The marketplace identifier (e.g., 'amazon').
     * @return array<string, string> The corresponding mapping array.
     * @throws InvalidArgumentException If the marketplace is unknown.
     */
    private static function getMappingForMarketplace(string $marketplace): array
    {
        return match (strtolower($marketplace)) {
            'amazon' => self::$amazonUnitMappings,
            // Add other marketplaces here, e.g.:
            // 'ebay' => self::$ebayUnitMappings,
            default => throw new InvalidArgumentException("Unknown or unsupported marketplace: '{$marketplace}'"),
            // Alternatively, fallback to base map:
            // default => self::$baseUnitMappings,
        };
    }

    /**
     * Creates a DimensionDto from a value and a marketplace unit string.
     *
     * @param float $value The numeric value of the dimension.
     * @param string $marketplaceUnit The unit string (e.g., 'centimeters', 'inches').
     * @param string $marketplace The target marketplace (e.g., 'amazon'). Defaults to 'amazon'.
     * @return DimensionDto
     * @throws InvalidArgumentException If the unit or marketplace is unknown, or the unit is not a dimension unit for that marketplace.
     */
    public static function createDimensionFromMarketplace(float $value, string $marketplaceUnit, string $marketplace = 'amazon'): DimensionDto
    {
        $mapping = self::getMappingForMarketplace($marketplace);
        $unitKey = strtolower(trim($marketplaceUnit));

        if (!isset($mapping[$unitKey])) {
            throw new InvalidArgumentException("Unknown unit '{$marketplaceUnit}' for marketplace '{$marketplace}'");
        }

        $enumValue = $mapping[$unitKey];
        $dimensionEnum = DimensionEnum::tryFrom($enumValue);

        if ($dimensionEnum === null) {
            throw new InvalidArgumentException("Unit '{$marketplaceUnit}' is not a valid dimension unit for marketplace '{$marketplace}'.");
        }

        return new DimensionDto($value, $dimensionEnum);
    }

    /**
     * Creates a WeightDto from a value and a marketplace unit string.
     *
     * @param float $value The numeric value of the weight.
     * @param string $marketplaceUnit The unit string (e.g., 'kilograms', 'pounds').
     * @param string $marketplace The target marketplace (e.g., 'amazon'). Defaults to 'amazon'.
     * @return WeightDto
     * @throws InvalidArgumentException If the unit or marketplace is unknown, or the unit is not a weight unit for that marketplace.
     */
    public static function createWeightFromMarketplace(float $value, string $marketplaceUnit, string $marketplace = 'amazon'): WeightDto
    {
        $mapping = self::getMappingForMarketplace($marketplace);
        $unitKey = strtolower(trim($marketplaceUnit));

        if (!isset($mapping[$unitKey])) {
            throw new InvalidArgumentException("Unknown unit '{$marketplaceUnit}' for marketplace '{$marketplace}'");
        }

        $enumValue = $mapping[$unitKey];
        $weightEnum = WeightEnum::tryFrom($enumValue);

        if ($weightEnum === null) {
            throw new InvalidArgumentException("Unit '{$marketplaceUnit}' is not a valid weight unit for marketplace '{$marketplace}'.");
        }

        return new WeightDto($value, $weightEnum);
    }

    /**
     * Attempts to create either a DimensionDto or WeightDto based on the unit and marketplace.
     *
     * @param float $value The numeric value.
     * @param string $marketplaceUnit The unit string.
     * @param string $marketplace The target marketplace (e.g., 'amazon'). Defaults to 'amazon'.
     * @return DimensionDto|WeightDto
     * @throws InvalidArgumentException If the unit or marketplace is unknown.
     */
    public static function createFromMarketplace(float $value, string $marketplaceUnit, string $marketplace = 'amazon'): DimensionDto|WeightDto
    {
        $mapping = self::getMappingForMarketplace($marketplace);
        $unitKey = strtolower(trim($marketplaceUnit));

        if (!isset($mapping[$unitKey])) {
            throw new InvalidArgumentException("Unknown unit '{$marketplaceUnit}' for marketplace '{$marketplace}'");
        }

        $enumValue = $mapping[$unitKey];

        $dimensionEnum = DimensionEnum::tryFrom($enumValue);
        if ($dimensionEnum !== null) {
            return new DimensionDto($value, $dimensionEnum);
        }

        $weightEnum = WeightEnum::tryFrom($enumValue);
        if ($weightEnum !== null) {
            return new WeightDto($value, $weightEnum);
        }

        // This part should theoretically not be reached if mappings are correct
        throw new InvalidArgumentException("Unit '{$marketplaceUnit}' for marketplace '{$marketplace}' could not be mapped to a known Dimension or Weight Enum.");
    }
}
