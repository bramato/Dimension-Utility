<?php

namespace Bramato\DimensionUtility\Dto;

use Bramato\DimensionUtility\Enum\DataStorageEnum;
use Bramato\DimensionUtility\Services\DataStorageConversionService;

/**
 * Represents a data storage measurement value and its unit (binary prefixes).
 */
class DataStorageDto
{
    /**
     * Creates a new DataStorageDto instance.
     *
     * @param float $value The numeric value of the measurement.
     * @param DataStorageEnum $unit The unit of the measurement.
     */
    public function __construct(
        public float $value,
        public DataStorageEnum $unit
    ) {}

    /**
     * Static factory method to create a DataStorageDto from a value and unit string.
     *
     * @param float $value The numeric value of the measurement.
     * @param string $unit The string representation of the unit (case-sensitive, e.g., 'MEBIBYTE').
     * @return static
     * @throws \ValueError if the unit string is not a valid case in DataStorageEnum.
     */
    public static function create(float $value, string $unit): self
    {
        return new self($value, DataStorageEnum::from($unit));
    }

    /**
     * Returns a string representation of the measurement (value and unit).
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value . ' ' . $this->unit->value;
    }

    /**
     * Converts the measurement to Bytes (B).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toB(): DataStorageDto
    {
        return DataStorageConversionService::create($this)->convert(DataStorageEnum::BYTE);
    }

    /**
     * Converts the measurement to Kibibytes (KiB).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toKiB(): DataStorageDto
    {
        return DataStorageConversionService::create($this)->convert(DataStorageEnum::KIBIBYTE);
    }

    /**
     * Converts the measurement to Mebibytes (MiB).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toMiB(): DataStorageDto
    {
        return DataStorageConversionService::create($this)->convert(DataStorageEnum::MEBIBYTE);
    }

    /**
     * Converts the measurement to Gibibytes (GiB).
     *
     * @return static A new DTO instance with the converted value and unit.
     */
    public function toGiB(): DataStorageDto
    {
        return DataStorageConversionService::create($this)->convert(DataStorageEnum::GIBIBYTE);
    }
}
