<?php

namespace Bramato\DimensionUtility\Services;

use Bramato\DimensionUtility\Dto\TemperatureDto;
use Bramato\DimensionUtility\Enum\TemperatureEnum;
use InvalidArgumentException;

/**
 * Service for converting temperature measurements between different units.
 */
class TemperatureConversionService
{
    /**
     * The TemperatureDto instance to perform conversions on.
     */
    public TemperatureDto $temperatureDto;

    /**
     * Creates a new TemperatureConversionService instance.
     *
     * @param TemperatureDto $temperatureDto The DTO containing the initial value and unit.
     */
    public function __construct(TemperatureDto $temperatureDto)
    {
        $this->temperatureDto = $temperatureDto;
    }

    /**
     * Static factory method to create a TemperatureConversionService instance.
     *
     * @param TemperatureDto $temperatureDto The DTO containing the initial value and unit.
     * @return static
     */
    public static function create(TemperatureDto $temperatureDto): self
    {
        return new self($temperatureDto);
    }

    /**
     * Converts the temperature measurement to the target unit.
     *
     * @param TemperatureEnum $targetUnit The unit to convert to.
     * @return TemperatureDto A new DTO instance with the converted value and target unit.
     * @throws InvalidArgumentException if the conversion units are not supported (should not happen with Enum).
     */
    public function convert(TemperatureEnum $targetUnit): TemperatureDto
    {
        $fromUnit = $this->temperatureDto->unit;
        $value = $this->temperatureDto->value;

        if ($fromUnit === $targetUnit) {
            return $this->temperatureDto; // No conversion needed
        }

        // Convert input value to Celsius first (base unit for calculation)
        $valueInCelsius = match ($fromUnit) {
            TemperatureEnum::CELSIUS => $value,
            TemperatureEnum::FAHRENHEIT => ($value - 32) * 5 / 9,
            TemperatureEnum::KELVIN => $value - 273.15,
        };

        // Convert from Celsius to the target unit
        $convertedValue = match ($targetUnit) {
            TemperatureEnum::CELSIUS => $valueInCelsius,
            TemperatureEnum::FAHRENHEIT => ($valueInCelsius * 9 / 5) + 32,
            TemperatureEnum::KELVIN => $valueInCelsius + 273.15,
        };

        return new TemperatureDto($convertedValue, $targetUnit);
    }
}
