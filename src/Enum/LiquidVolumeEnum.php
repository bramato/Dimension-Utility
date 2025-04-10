<?php

namespace Bramato\DimensionUtility\Enum;

enum LiquidVolumeEnum: string
{
    case ML = 'ML';          // Milliliter - Metric unit of volume.
    case L = 'L';            // Liter - Metric unit of volume.
    case FL_OZ = 'FL_OZ';    // Fluid Ounce - Imperial unit of volume.
    case GAL = 'GAL';        // Gallon - Imperial unit of volume.
    case PT = 'PT';          // Pint - Imperial unit of volume.
    case QT = 'QT';          // Quart - Imperial unit of volume.
    case C = 'C';            // Cup - Imperial unit of volume.
}
