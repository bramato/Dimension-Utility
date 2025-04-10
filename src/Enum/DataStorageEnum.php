<?php

namespace Bramato\DimensionUtility\Enum;

/**
 * Enum representing different units of data storage measurement (binary prefixes).
 */
enum DataStorageEnum: string
{
    case BYTE = 'BYTE';         // Byte (B)
    case KIBIBYTE = 'KIBIBYTE'; // Kibibyte (KiB) = 1024 B
    case MEBIBYTE = 'MEBIBYTE'; // Mebibyte (MiB) = 1024 KiB
    case GIBIBYTE = 'GIBIBYTE'; // Gibibyte (GiB) = 1024 MiB
    case TEBIBYTE = 'TEBIBYTE'; // Tebibyte (TiB) = 1024 GiB
    case PEBIBYTE = 'PEBIBYTE'; // Pebibyte (PiB) = 1024 TiB
    // We could add decimal prefixes (KB, MB, GB) if needed, but binary is common for storage capacity
}
