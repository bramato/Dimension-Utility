<?php

namespace Bramato\DimensionUtility\Enum;

/**
 * Enum representing different units of temperature measurement.
 */
enum TemperatureEnum: string
{
    case CELSIUS = 'CELSIUS';         // Degrees Celsius
    case FAHRENHEIT = 'FAHRENHEIT';   // Degrees Fahrenheit
    case KELVIN = 'KELVIN';           // Kelvin
}
