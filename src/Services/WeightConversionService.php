<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\WeightEnum;
use InvalidArgumentException;

class WeightConversionService
{
    // Conversion table between all units (from unit to other units)
    private const CONVERSION_TABLE = [
        // Conversion factors for GRAM to other units
        WeightEnum::GRAM->value => [
            WeightEnum::GRAM->value => 1, // 1 gram = 1 gram
            WeightEnum::KILOGRAM->value => 0.001, // 1 gram = 0.001 kilograms
            WeightEnum::OUNCE->value => 0.035274, // 1 gram = 0.035274 ounces
            WeightEnum::POUND->value => 0.00220462, // 1 gram = 0.00220462 pounds
            WeightEnum::MILLIGRAM->value => 1000, // 1 gram = 1000 milligrams
            WeightEnum::TON->value => 0.000001, // 1 gram = 0.000001 tons
            WeightEnum::STONE->value => 0.000157473, // 1 gram = 0.000157473 stones
            WeightEnum::MICROGRAM->value => 1000000, // 1 gram = 1000000 micrograms
            WeightEnum::NANOGRAM->value => 1000000000, // 1 gram = 1000000000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 0.220462, // 1 gram = 0.220462 hundredths of a pound
        ],
        // Conversion factors for KILOGRAM to other units
        WeightEnum::KILOGRAM->value => [
            WeightEnum::GRAM->value => 1000, // 1 kilogram = 1000 grams
            WeightEnum::KILOGRAM->value => 1, // 1 kilogram = 1 kilogram
            WeightEnum::OUNCE->value => 35.274, // 1 kilogram = 35.274 ounces
            WeightEnum::POUND->value => 2.20462, // 1 kilogram = 2.20462 pounds
            WeightEnum::MILLIGRAM->value => 1000000, // 1 kilogram = 1000000 milligrams
            WeightEnum::TON->value => 0.001, // 1 kilogram = 0.001 tons
            WeightEnum::STONE->value => 0.157473, // 1 kilogram = 0.157473 stones
            WeightEnum::MICROGRAM->value => 1000000000, // 1 kilogram = 1000000000 micrograms
            WeightEnum::NANOGRAM->value => 1000000000000, // 1 kilogram = 1000000000000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 220.462, // 1 kilogram = 220.462 hundredths of a pound
        ],
        // Conversion factors for OUNCE to other units
        WeightEnum::OUNCE->value => [
            WeightEnum::GRAM->value => 28.3495, // 1 ounce = 28.3495 grams
            WeightEnum::KILOGRAM->value => 0.0283495, // 1 ounce = 0.0283495 kilograms
            WeightEnum::OUNCE->value => 1, // 1 ounce = 1 ounce
            WeightEnum::POUND->value => 0.0625, // 1 ounce = 0.0625 pounds
            WeightEnum::MILLIGRAM->value => 28349.5, // 1 ounce = 28349.5 milligrams
            WeightEnum::TON->value => 0.0000283495, // 1 ounce = 0.0000283495 tons
            WeightEnum::STONE->value => 0.00446429, // 1 ounce = 0.00446429 stones
            WeightEnum::MICROGRAM->value => 28349500, // 1 ounce = 28349500 micrograms
            WeightEnum::NANOGRAM->value => 28349500000, // 1 ounce = 28349500000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 6.25, // 1 ounce = 6.25 hundredths of a pound
        ],
        // Conversion factors for POUND to other units
        WeightEnum::POUND->value => [
            WeightEnum::GRAM->value => 453.592, // 1 pound = 453.592 grams
            WeightEnum::KILOGRAM->value => 0.453592, // 1 pound = 0.453592 kilograms
            WeightEnum::OUNCE->value => 16, // 1 pound = 16 ounces
            WeightEnum::POUND->value => 1, // 1 pound = 1 pound
            WeightEnum::MILLIGRAM->value => 453592, // 1 pound = 453592 milligrams
            WeightEnum::TON->value => 0.000453592, // 1 pound = 0.000453592 tons
            WeightEnum::STONE->value => 0.0714286, // 1 pound = 0.0714286 stones
            WeightEnum::MICROGRAM->value => 453592000, // 1 pound = 453592000 micrograms
            WeightEnum::NANOGRAM->value => 453592000000, // 1 pound = 453592000000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 100, // 1 pound = 100 hundredths of a pound
        ],
        // Conversion factors for MILLIGRAM to other units
        WeightEnum::MILLIGRAM->value => [
            WeightEnum::GRAM->value => 0.001, // 1 milligram = 0.001 grams
            WeightEnum::KILOGRAM->value => 0.000001, // 1 milligram = 0.000001 kilograms
            WeightEnum::OUNCE->value => 0.000035274, // 1 milligram = 0.000035274 ounces
            WeightEnum::POUND->value => 0.00000220462, // 1 milligram = 0.00000220462 pounds
            WeightEnum::MILLIGRAM->value => 1, // 1 milligram = 1 milligram
            WeightEnum::TON->value => 0.000000001, // 1 milligram = 0.000000001 tons
            WeightEnum::STONE->value => 0.000000157473, // 1 milligram = 0.000000157473 stones
            WeightEnum::MICROGRAM->value => 1000, // 1 milligram = 1000 micrograms
            WeightEnum::NANOGRAM->value => 1000000, // 1 milligram = 1000000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 0.000220462, // 1 milligram = 0.000220462 hundredths of a pound
        ],
        // Conversion factors for TON to other units
        WeightEnum::TON->value => [
            WeightEnum::GRAM->value => 1000000, // 1 ton = 1000000 grams
            WeightEnum::KILOGRAM->value => 1000, // 1 ton = 1000 kilograms
            WeightEnum::OUNCE->value => 35274, // 1 ton = 35274 ounces
            WeightEnum::POUND->value => 2204.62, // 1 ton = 2204.62 pounds
            WeightEnum::MILLIGRAM->value => 1000000000, // 1 ton = 1000000000 milligrams
            WeightEnum::TON->value => 1, // 1 ton = 1 ton
            WeightEnum::STONE->value => 157.473, // 1 ton = 157.473 stones
            WeightEnum::MICROGRAM->value => 1000000000000, // 1 ton = 1000000000000 micrograms
            WeightEnum::NANOGRAM->value => 1000000000000000, // 1 ton = 1000000000000000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 220462, // 1 ton = 220462 hundredths of a pound
        ],
        // Conversion factors for STONE to other units
        WeightEnum::STONE->value => [
            WeightEnum::GRAM->value => 6350.29, // 1 stone = 6350.29 grams
            WeightEnum::KILOGRAM->value => 6.35029, // 1 stone = 6.35029 kilograms
            WeightEnum::OUNCE->value => 224, // 1 stone = 224 ounces
            WeightEnum::POUND->value => 14, // 1 stone = 14 pounds
            WeightEnum::MILLIGRAM->value => 6350290, // 1 stone = 6350290 milligrams
            WeightEnum::TON->value => 0.00635029, // 1 stone = 0.00635029 tons
            WeightEnum::STONE->value => 1, // 1 stone = 1 stone
            WeightEnum::MICROGRAM->value => 6350290000, // 1 stone = 6350290000 micrograms
            WeightEnum::NANOGRAM->value => 6350290000000, // 1 stone = 6350290000000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 1400, // 1 stone = 1400 hundredths of a pound
        ],
        // Conversion factors for MICROGRAM to other units
        WeightEnum::MICROGRAM->value => [
            WeightEnum::GRAM->value => 0.000001, // 1 microgram = 0.000001 grams
            WeightEnum::KILOGRAM->value => 0.000000001, // 1 microgram = 0.000000001 kilograms
            WeightEnum::OUNCE->value => 0.000000035274, // 1 microgram = 0.000000035274 ounces
            WeightEnum::POUND->value => 0.00000000220462, // 1 microgram = 0.00000000220462 pounds
            WeightEnum::MILLIGRAM->value => 0.001, // 1 microgram = 0.001 milligrams
            WeightEnum::TON->value => 0.000000000001, // 1 microgram = 0.000000000001 tons
            WeightEnum::STONE->value => 0.000000000157473, // 1 microgram = 0.000000000157473 stones
            WeightEnum::MICROGRAM->value => 1, // 1 microgram = 1 microgram
            WeightEnum::NANOGRAM->value => 1000, // 1 microgram = 1000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 0.000000220462, // 1 microgram = 0.000000220462 hundredths of a pound
        ],
        // Conversion factors for NANOGRAM to other units
        WeightEnum::NANOGRAM->value => [
            WeightEnum::GRAM->value => 0.000000001, // 1 nanogram = 0.000000001 grams
            WeightEnum::KILOGRAM->value => 0.000000000001, // 1 nanogram = 0.000000000001 kilograms
            WeightEnum::OUNCE->value => 0.000000000035274, // 1 nanogram = 0.000000000035274 ounces
            WeightEnum::POUND->value => 0.00000000000220462, // 1 nanogram = 0.00000000000220462 pounds
            WeightEnum::MILLIGRAM->value => 0.000001, // 1 nanogram = 0.000001 milligrams
            WeightEnum::TON->value => 0.000000000000001, // 1 nanogram = 0.000000000000001 tons
            WeightEnum::STONE->value => 0.000000000000157473, // 1 nanogram = 0.000000000000157473 stones
            WeightEnum::MICROGRAM->value => 0.001, // 1 nanogram = 0.001 micrograms
            WeightEnum::NANOGRAM->value => 1, // 1 nanogram = 1 nanogram
            WeightEnum::HUNDREDTHS_POUND->value => 0.000000000220462, // 1 nanogram = 0.000000000220462 hundredths of a pound
        ],
        // Conversion factors for HUNDREDTHS_POUND to other units
        WeightEnum::HUNDREDTHS_POUND->value => [
            WeightEnum::GRAM->value => 4.53592, // 1 hundredth of a pound = 4.53592 grams
            WeightEnum::KILOGRAM->value => 0.00453592, // 1 hundredth of a pound = 0.00453592 kilograms
            WeightEnum::OUNCE->value => 0.16, // 1 hundredth of a pound = 0.16 ounces
            WeightEnum::POUND->value => 0.01, // 1 hundredth of a pound = 0.01 pounds
            WeightEnum::MILLIGRAM->value => 4535.92, // 1 hundredth of a pound = 4535.92 milligrams
            WeightEnum::TON->value => 0.00000453592, // 1 hundredth of a pound = 0.00000453592 tons
            WeightEnum::STONE->value => 0.000714286, // 1 hundredth of a pound = 0.000714286 stones
            WeightEnum::MICROGRAM->value => 4535920, // 1 hundredth of a pound = 4535920 micrograms
            WeightEnum::NANOGRAM->value => 4535920000, // 1 hundredth of a pound = 4535920000 nanograms
            WeightEnum::HUNDREDTHS_POUND->value => 1, // 1 hundredth of a pound = 1 hundredth of a pound
        ],
    ];

    public function __construct(public WeightDto $weightDto) {}

    /**
     * Static method to create a WeightDto with error handling.
     *
     * @param WeightDto $weightDto
     * @return WeightConversionService The DTO created.
     */
    public static function create(WeightDto $weightDto): self
    {
        return new self($weightDto);
    }

    /**
     * Convert a given WeightDto to the desired unit.
     *
     * @param WeightDto $dto The DTO containing the value and current unit.
     * @param WeightEnum $targetUnit The target unit to convert to.
     * @return WeightDto The DTO with the converted value.
     * @throws InvalidArgumentException if the unit is not supported.
     */
    public function convert(WeightEnum $targetUnit): WeightDto
    {
        // Retrieve the conversion factor from the current unit to the target unit
        $conversionFactor = $this->getConversionFactor($this->weightDto->unit, $targetUnit);

        // Convert the value using the factor
        $convertedValue = $this->weightDto->value * $conversionFactor;

        return new WeightDto($convertedValue, $targetUnit); // Return a new WeightDto with the converted value and target unit
    }

    /**
     * Get the conversion factor between two units.
     *
     * @param WeightEnum $fromUnit The unit to convert from.
     * @param WeightEnum $toUnit The unit to convert to.
     * @return float The conversion factor.
     * @throws InvalidArgumentException if the units are not supported.
     */
    private function getConversionFactor(WeightEnum $fromUnit, WeightEnum $toUnit): float
    {
        // Check if the conversion factor exists in the conversion table
        if (!isset(self::CONVERSION_TABLE[$fromUnit->value][$toUnit->value])) {
            throw new InvalidArgumentException("Conversion from {$fromUnit->value} to {$toUnit->value} is not supported.");
        }

        // Return the conversion factor from the table
        return self::CONVERSION_TABLE[$fromUnit->value][$toUnit->value];
    }
}
