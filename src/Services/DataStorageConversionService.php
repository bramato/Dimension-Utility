<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\DataStorageDto;
use Bramato\DimensionUtility\Enum\DataStorageEnum;
use InvalidArgumentException;

/**
 * Service for converting data storage measurements between different units (binary prefixes).
 */
class DataStorageConversionService
{
    // Conversion factors based on powers of 1024, relative to BYTE
    private const FACTORS_FROM_BYTE = [
        DataStorageEnum::BYTE->value => 1,                      // 1
        DataStorageEnum::KIBIBYTE->value => 1024,                 // 1024^1
        DataStorageEnum::MEBIBYTE->value => 1048576,              // 1024^2
        DataStorageEnum::GIBIBYTE->value => 1073741824,           // 1024^3
        DataStorageEnum::TEBIBYTE->value => 1099511627776,        // 1024^4
        DataStorageEnum::PEBIBYTE->value => 1125899906842624,     // 1024^5
    ];

    public DataStorageDto $dataStorageDto;

    public function __construct(DataStorageDto $dataStorageDto)
    {
        $this->dataStorageDto = $dataStorageDto;
    }

    public static function create(DataStorageDto $dataStorageDto): self
    {
        return new self($dataStorageDto);
    }

    /**
     * Converts the data storage measurement to the target unit.
     *
     * @param DataStorageEnum $targetUnit The unit to convert to.
     * @return DataStorageDto A new DTO instance with the converted value and target unit.
     * @throws InvalidArgumentException if the conversion units are not supported.
     */
    public function convert(DataStorageEnum $targetUnit): DataStorageDto
    {
        $factor = $this->getConversionFactor($this->dataStorageDto->unit, $targetUnit);
        // Use bcmath for potentially large numbers in data storage conversions
        $valueStr = (string)$this->dataStorageDto->value;
        $factorStr = (string)$factor;
        // Determine scale (number of decimal places) - adjust if needed
        $scale = max($this->getDecimalPlaces($valueStr), $this->getDecimalPlaces($factorStr), 10);

        $convertedValueStr = bcmul($valueStr, $factorStr, $scale);
        // Convert back to float, potential precision loss for huge values but practical
        $convertedValue = (float)$convertedValueStr;

        return new DataStorageDto($convertedValue, $targetUnit);
    }

    /**
     * Calculates the conversion factor between two data storage units via BYTE.
     */
    private function getConversionFactor(DataStorageEnum $fromUnit, DataStorageEnum $toUnit): string // Return string for bcmath
    {
        if ($fromUnit === $toUnit) {
            return '1.0';
        }

        // Factor to convert fromUnit to BYTES (value * factor)
        $factorFromBase = self::FACTORS_FROM_BYTE[$fromUnit->value] ?? null;
        if ($factorFromBase === null) {
            throw new InvalidArgumentException("Base conversion factor not found for data storage unit: {$fromUnit->value}");
        }

        // Factor to convert targetUnit to BYTES
        $factorToBaseTarget = self::FACTORS_FROM_BYTE[$toUnit->value] ?? null;
        if ($factorToBaseTarget === null) {
            throw new InvalidArgumentException("Target conversion factor not found for data storage unit: {$toUnit->value}");
        }

        // To convert FROM fromUnit TO toUnit: multiply by (factorFromBase / factorToBaseTarget)
        // Use bcmath for division to maintain precision
        $factor = bcdiv((string)$factorFromBase, (string)$factorToBaseTarget, 50); // High scale for intermediate calc

        return $factor;
    }

    /**
     * Helper to get decimal places for bcmath scale.
     */
    private function getDecimalPlaces(string $number): int
    {
        if (strpos($number, '.') !== false) {
            return strlen(substr($number, strpos($number, '.') + 1));
        }
        return 0;
    }
}
