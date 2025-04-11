<?php

namespace Bramato\DimensionUtility\Enum;

/**
 * Enum representing different units of speed measurement.
 */
enum SpeedEnum: string
{
    case METER_PER_SECOND = 'METER_PER_SECOND';     // Meters per second (m/s) - SI base unit
    case KILOMETER_PER_HOUR = 'KILOMETER_PER_HOUR'; // Kilometers per hour (km/h)
    case MILE_PER_HOUR = 'MILE_PER_HOUR';         // Miles per hour (mph)
    case KNOT = 'KNOT';                         // Knots (nautical miles per hour)
    case FOOT_PER_SECOND = 'FOOT_PER_SECOND';       // Feet per second (ft/s)
}
