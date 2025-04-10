<?php

namespace Bramato\DimensionUtility\Enum;

enum DimensionEnum: string
{
    case INCH = 'INCH';           // The imperial unit of length equal to one twelfth of a foot.
    case CENTIMETER = 'CENTIMETER'; // A metric unit of length, equal to one hundredth of a meter.
    case METER = 'METER';           // The base unit of length in the metric system.
    case KILOMETER = 'KILOMETER';   // A metric unit of length equal to one thousand meters.
    case FOOT = 'FOOT';             // An imperial unit of length equal to 12 inches.
    case YARD = 'YARD';             // An imperial unit of length equal to 3 feet.
    case MILE = 'MILE';             // An imperial unit of length equal to 5280 feet.
    case MILLIMETER = 'MILLIMETER'; // A metric unit of length equal to one thousandth of a meter.
    case MICROMETER = 'MICROMETER'; // A metric unit of length equal to one millionth of a meter.
    case NANOMETER = 'NANOMETER';   // A metric unit of length equal to one billionth of a meter.
    case DECIMETER = 'DECIMETER';   // A metric unit of length equal to one tenth of a meter.
}
