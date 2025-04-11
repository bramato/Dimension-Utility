<?php

namespace Bramato\DimensionUtility\Enum;

/**
 * Enum representing different units of area measurement.
 */
enum AreaEnum: string
{
    case SQ_METER = 'SQ_METER';             // Square Meter
    case SQ_KILOMETER = 'SQ_KILOMETER';       // Square Kilometer
    case SQ_CENTIMETER = 'SQ_CENTIMETER';     // Square Centimeter
    case SQ_MILLIMETER = 'SQ_MILLIMETER';     // Square Millimeter
    case SQ_FOOT = 'SQ_FOOT';               // Square Foot
    case SQ_YARD = 'SQ_YARD';               // Square Yard
    case SQ_INCH = 'SQ_INCH';               // Square Inch
    case SQ_MILE = 'SQ_MILE';               // Square Mile
    case ACRE = 'ACRE';                   // Acre
    case HECTARE = 'HECTARE';               // Hectare
}
