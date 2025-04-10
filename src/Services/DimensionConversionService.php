<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use InvalidArgumentException;

class DimensionConversionService
{
    // Conversion table between all units (from unit to other units)
    private const CONVERSION_TABLE = [
        DimensionEnum::INCH->value => [
            DimensionEnum::INCH->value => 1,
            DimensionEnum::CENTIMETER->value => 2.54,  // 1 inch = 2.54 centimeters
            DimensionEnum::METER->value => 0.0254,      // 1 inch = 0.0254 meters
            DimensionEnum::KILOMETER->value => 0.0000254, // 1 inch = 0.0000254 kilometers
            DimensionEnum::FOOT->value => 0.0833333,    // 1 inch = 0.0833333 feet
            DimensionEnum::YARD->value => 0.0277778,    // 1 inch = 0.0277778 yards
            DimensionEnum::MILE->value => 0.0000157828, // 1 inch = 0.0000157828 miles
            DimensionEnum::MILLIMETER->value => 25.4,   // 1 inch = 25.4 millimeters
            DimensionEnum::MICROMETER->value => 25400,  // 1 inch = 25400 micrometers
            DimensionEnum::NANOMETER->value => 25400000, // 1 inch = 25400000 nanometers
            DimensionEnum::DECIMETER->value => 0.254,   // 1 inch = 0.254 decimeters
        ],
        DimensionEnum::CENTIMETER->value => [
            DimensionEnum::INCH->value => 0.393701,    // 1 centimeter = 0.393701 inches
            DimensionEnum::CENTIMETER->value => 1,
            DimensionEnum::METER->value => 0.01,       // 1 centimeter = 0.01 meters
            DimensionEnum::KILOMETER->value => 0.00001, // 1 centimeter = 0.00001 kilometers
            DimensionEnum::FOOT->value => 0.0328084,   // 1 centimeter = 0.0328084 feet
            DimensionEnum::YARD->value => 0.0109361,   // 1 centimeter = 0.0109361 yards
            DimensionEnum::MILE->value => 0.0000062137, // 1 centimeter = 0.0000062137 miles
            DimensionEnum::MILLIMETER->value => 10,    // 1 centimeter = 10 millimeters
            DimensionEnum::MICROMETER->value => 10000, // 1 centimeter = 10000 micrometers
            DimensionEnum::NANOMETER->value => 10000000, // 1 centimeter = 10000000 nanometers
            DimensionEnum::DECIMETER->value => 0.1,    // 1 centimeter = 0.1 decimeters
        ],
        DimensionEnum::METER->value => [
            DimensionEnum::INCH->value => 39.3701,     // 1 meter = 39.3701 inches
            DimensionEnum::CENTIMETER->value => 100,   // 1 meter = 100 centimeters
            DimensionEnum::METER->value => 1,
            DimensionEnum::KILOMETER->value => 0.001,  // 1 meter = 0.001 kilometers
            DimensionEnum::FOOT->value => 3.28084,     // 1 meter = 3.28084 feet
            DimensionEnum::YARD->value => 1.09361,     // 1 meter = 1.09361 yards
            DimensionEnum::MILE->value => 0.000621371, // 1 meter = 0.000621371 miles
            DimensionEnum::MILLIMETER->value => 1000,  // 1 meter = 1000 millimeters
            DimensionEnum::MICROMETER->value => 1000000, // 1 meter = 1000000 micrometers
            DimensionEnum::NANOMETER->value => 1000000000, // 1 meter = 1000000000 nanometers
            DimensionEnum::DECIMETER->value => 10,     // 1 meter = 10 decimeters
        ],
        DimensionEnum::KILOMETER->value => [
            DimensionEnum::INCH->value => 39370.1,     // 1 kilometer = 39370.1 inches
            DimensionEnum::CENTIMETER->value => 100000, // 1 kilometer = 100000 centimeters
            DimensionEnum::METER->value => 1000,       // 1 kilometer = 1000 meters
            DimensionEnum::KILOMETER->value => 1,
            DimensionEnum::FOOT->value => 3280.84,     // 1 kilometer = 3280.84 feet
            DimensionEnum::YARD->value => 1093.61,     // 1 kilometer = 1093.61 yards
            DimensionEnum::MILE->value => 0.621371,    // 1 kilometer = 0.621371 miles
            DimensionEnum::MILLIMETER->value => 1000000, // 1 kilometer = 1000000 millimeters
            DimensionEnum::MICROMETER->value => 1000000000, // 1 kilometer = 1000000000 micrometers
            DimensionEnum::NANOMETER->value => 1000000000000, // 1 kilometer = 1000000000000 nanometers
            DimensionEnum::DECIMETER->value => 10000,  // 1 kilometer = 10000 decimeters
        ],
        DimensionEnum::FOOT->value => [
            DimensionEnum::INCH->value => 12,          // 1 foot = 12 inches
            DimensionEnum::CENTIMETER->value => 30.48, // 1 foot = 30.48 centimeters
            DimensionEnum::METER->value => 0.3048,     // 1 foot = 0.3048 meters
            DimensionEnum::KILOMETER->value => 0.0003048, // 1 foot = 0.0003048 kilometers
            DimensionEnum::FOOT->value => 1,
            DimensionEnum::YARD->value => 0.333333,    // 1 foot = 0.333333 yards
            DimensionEnum::MILE->value => 0.000189394, // 1 foot = 0.000189394 miles
            DimensionEnum::MILLIMETER->value => 304.8, // 1 foot = 304.8 millimeters
            DimensionEnum::MICROMETER->value => 304800, // 1 foot = 304800 micrometers
            DimensionEnum::NANOMETER->value => 304800000, // 1 foot = 304800000 nanometers
            DimensionEnum::DECIMETER->value => 3.048,  // 1 foot = 3.048 decimeters
        ],
        DimensionEnum::YARD->value => [
            DimensionEnum::INCH->value => 36,          // 1 yard = 36 inches
            DimensionEnum::CENTIMETER->value => 91.44, // 1 yard = 91.44 centimeters
            DimensionEnum::METER->value => 0.9144,     // 1 yard = 0.9144 meters
            DimensionEnum::KILOMETER->value => 0.0009144, // 1 yard = 0.0009144 kilometers
            DimensionEnum::FOOT->value => 3,           // 1 yard = 3 feet
            DimensionEnum::YARD->value => 1,
            DimensionEnum::MILE->value => 0.000568182, // 1 yard = 0.000568182 miles
            DimensionEnum::MILLIMETER->value => 914.4, // 1 yard = 914.4 millimeters
            DimensionEnum::MICROMETER->value => 914400, // 1 yard = 914400 micrometers
            DimensionEnum::NANOMETER->value => 914400000, // 1 yard = 914400000 nanometers
            DimensionEnum::DECIMETER->value => 9.144,  // 1 yard = 9.144 decimeters
        ],
        DimensionEnum::MILE->value => [
            DimensionEnum::INCH->value => 63360,       // 1 mile = 63360 inches
            DimensionEnum::CENTIMETER->value => 160934, // 1 mile = 160934 centimeters
            DimensionEnum::METER->value => 1609.34,    // 1 mile = 1609.34 meters
            DimensionEnum::KILOMETER->value => 1.60934, // 1 mile = 1.60934 kilometers
            DimensionEnum::FOOT->value => 5280,        // 1 mile = 5280 feet
            DimensionEnum::YARD->value => 1760,        // 1 mile = 1760 yards
            DimensionEnum::MILE->value => 1,
            DimensionEnum::MILLIMETER->value => 1609340, // 1 mile = 1609340 millimeters
            DimensionEnum::MICROMETER->value => 1609340000, // 1 mile = 1609340000 micrometers
            DimensionEnum::NANOMETER->value => 1609340000000, // 1 mile = 1609340000000 nanometers
            DimensionEnum::DECIMETER->value => 16093.4, // 1 mile = 16093.4 decimeters
        ],
        DimensionEnum::MILLIMETER->value => [
            DimensionEnum::INCH->value => 0.0393701,   // 1 millimeter = 0.0393701 inches
            DimensionEnum::CENTIMETER->value => 0.1,   // 1 millimeter = 0.1 centimeters
            DimensionEnum::METER->value => 0.001,      // 1 millimeter = 0.001 meters
            DimensionEnum::KILOMETER->value => 0.000001, // 1 millimeter = 0.000001 kilometers
            DimensionEnum::FOOT->value => 0.00328084,  // 1 millimeter = 0.00328084 feet
            DimensionEnum::YARD->value => 0.00109361,  // 1 millimeter = 0.00109361 yards
            DimensionEnum::MILE->value => 0.000000621371, // 1 millimeter = 0.000000621371 miles
            DimensionEnum::MILLIMETER->value => 1,
            DimensionEnum::MICROMETER->value => 1000,  // 1 millimeter = 1000 micrometers
            DimensionEnum::NANOMETER->value => 1000000, // 1 millimeter = 1000000 nanometers
            DimensionEnum::DECIMETER->value => 0.01,   // 1 millimeter = 0.01 decimeters
        ],
        DimensionEnum::MICROMETER->value => [
            DimensionEnum::INCH->value => 0.0000393701, // 1 micrometer = 0.0000393701 inches
            DimensionEnum::CENTIMETER->value => 0.0001, // 1 micrometer = 0.0001 centimeters
            DimensionEnum::METER->value => 0.000001,   // 1 micrometer = 0.000001 meters
            DimensionEnum::KILOMETER->value => 0.000000001, // 1 micrometer = 0.000000001 kilometers
            DimensionEnum::FOOT->value => 0.00000328084, // 1 micrometer = 0.00000328084 feet
            DimensionEnum::YARD->value => 0.00000109361, // 1 micrometer = 0.00000109361 yards
            DimensionEnum::MILE->value => 0.000000000621371, // 1 micrometer = 0.000000000621371 miles
            DimensionEnum::MILLIMETER->value => 0.001,  // 1 micrometer = 0.001 millimeters
            DimensionEnum::MICROMETER->value => 1,
            DimensionEnum::NANOMETER->value => 1000,   // 1 micrometer = 1000 nanometers
            DimensionEnum::DECIMETER->value => 0.00001, // 1 micrometer = 0.00001 decimeters
        ],
        DimensionEnum::NANOMETER->value => [
            DimensionEnum::INCH->value => 0.0000000393701, // 1 nanometer = 0.0000000393701 inches
            DimensionEnum::CENTIMETER->value => 0.0000001, // 1 nanometer = 0.0000001 centimeters
            DimensionEnum::METER->value => 0.000000001, // 1 nanometer = 0.000000001 meters
            DimensionEnum::KILOMETER->value => 0.000000000001, // 1 nanometer = 0.000000000001 kilometers
            DimensionEnum::FOOT->value => 0.00000000328084, // 1 nanometer = 0.00000000328084 feet
            DimensionEnum::YARD->value => 0.00000000109361, // 1 nanometer = 0.00000000109361 yards
            DimensionEnum::MILE->value => 0.000000000000621371, // 1 nanometer = 0.000000000000621371 miles
            DimensionEnum::MILLIMETER->value => 0.000001, // 1 nanometer = 0.000001 millimeters
            DimensionEnum::MICROMETER->value => 0.001, // 1 nanometer = 0.001 micrometers
            DimensionEnum::NANOMETER->value => 1,
            DimensionEnum::DECIMETER->value => 0.00000001, // 1 nanometer = 0.00000001 decimeters
        ],
        DimensionEnum::DECIMETER->value => [
            DimensionEnum::INCH->value => 3.93701,    // 1 decimeter = 3.93701 inches
            DimensionEnum::CENTIMETER->value => 10,   // 1 decimeter = 10 centimeters
            DimensionEnum::METER->value => 0.1,       // 1 decimeter = 0.1 meters
            DimensionEnum::KILOMETER->value => 0.0001, // 1 decimeter = 0.0001 kilometers
            DimensionEnum::FOOT->value => 0.328084,   // 1 decimeter = 0.328084 feet
            DimensionEnum::YARD->value => 0.109361,   // 1 decimeter = 0.109361 yards
            DimensionEnum::MILE->value => 0.0000621371, // 1 decimeter = 0.0000621371 miles
            DimensionEnum::MILLIMETER->value => 100,  // 1 decimeter = 100 millimeters
            DimensionEnum::MICROMETER->value => 100000, // 1 decimeter = 100000 micrometers
            DimensionEnum::NANOMETER->value => 100000000, // 1 decimeter = 100000000 nanometers
            DimensionEnum::DECIMETER->value => 1,
        ],
    ];
    private DimensionDto $dimensionDto;

    public function __construct(DimensionDto $dimensionDto)
    {
        $this->dimensionDto = $dimensionDto;
    }

    /**
     * Static method to create a DimensionDto with error handling.
     *
     * @param DimensionDto $dimensionDto
     * @return DimensionConversionService The DTO created.
     */
    public static function create(DimensionDto $dimensionDto): self
    {
        return new self($dimensionDto);
    }

    /**
     * Convert a given DimensionDto to the desired unit.
     *
     * @param DimensionDto $dto The DTO containing the value and current unit.
     * @param DimensionEnum $targetUnit The target unit to convert to.
     * @return DimensionDto The DTO with the converted value.
     * @throws InvalidArgumentException if the unit is not supported.
     */
    public function convert(DimensionEnum $targetUnit): DimensionDto
    {
        // Retrieve the conversion factor from the current unit to the target unit
        $conversionFactor = $this->getConversionFactor($this->dimensionDto->unit, $targetUnit);
        // Convert the value using the factor
        $convertedValue = $this->dimensionDto->value * $conversionFactor;
        return new DimensionDto($convertedValue, $targetUnit);
    }

    /**
     * Get the conversion factor between two units.
     *
     * @param DimensionEnum $fromUnit The unit to convert from.
     * @param DimensionEnum $toUnit The unit to convert to.
     * @return float The conversion factor.
     * @throws InvalidArgumentException if the units are not supported.
     */
    private function getConversionFactor(DimensionEnum $fromUnit, DimensionEnum $toUnit): float
    {
        if (!isset(self::CONVERSION_TABLE[$fromUnit->value][$toUnit->value])) {
            throw new InvalidArgumentException("Conversion from {$fromUnit->value} to {$toUnit->value} is not supported.");
        }

        return self::CONVERSION_TABLE[$fromUnit->value][$toUnit->value];
    }
}
