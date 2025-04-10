<?php

namespace Bramato\DimensionUtility\Enum;

/**
 * Enum representing different units of pressure measurement.
 */
enum PressureEnum: string
{
    case PASCAL = 'PASCAL';                 // Pascal (Pa) - SI base unit
    case KILOPASCAL = 'KILOPASCAL';           // Kilopascal (kPa)
    case MEGAPASCAL = 'MEGAPASCAL';           // Megapascal (MPa)
    case BAR = 'BAR';                       // Bar
    case MILLIBAR = 'MILLIBAR';             // Millibar (mbar)
    case PSI = 'PSI';                       // Pounds per square inch (psi)
    case ATMOSPHERE = 'ATMOSPHERE';           // Standard atmosphere (atm)
    case TORR = 'TORR';                     // Torr (mmHg)
}
