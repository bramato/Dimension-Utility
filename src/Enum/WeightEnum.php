<?php

namespace Bramato\DimensionUtility\Enum;

enum WeightEnum: string
{
    case MILLIGRAM = 'MILLIGRAM';
    case GRAM = 'GRAM';         // Metric unit of mass equal to one thousandth of a kilogram.
    case KILOGRAM = 'KILOGRAM'; // Metric unit of mass.
    case OUNCE = 'OUNCE';       // The imperial unit of weight that is one sixteenth of a pound.
    case POUND = 'POUND';       // The imperial unit of weight.
    case TON = 'TON';           // Metric unit of mass equal to 1000 kilograms.
    case STONE = 'STONE';       // Imperial unit of weight equal to 14 pounds.
    case MICROGRAM = 'MICROGRAM'; // Metric unit of mass equal to one millionth of a gram.
    case NANOGRAM = 'NANOGRAM'; // Metric unit of mass equal to one billionth of a gram.
    case HUNDREDTHS_POUND = 'HUNDREDTHS_POUND'; // Imperial unit of weight equal to one hundredth of a pound.
}
