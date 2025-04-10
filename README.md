# Dimension Utility Package

A simple PHP package for handling and converting various units of measure (weight, dimension, liquid volume).

## Installation

You can install the package via Composer:

```bash
composer require bramato/dimensionutility
```

## Usage

The package provides DTOs (Data Transfer Objects) and Conversion Services for three types of measurements: Weight, Dimension, and Liquid Volume.

### Weight Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\WeightConversionService;

// Create a WeightDto
$initialWeight = new WeightDto(10, WeightEnum::KILOGRAM);
// Or using the static factory method (useful if unit comes from a string)
// $initialWeight = WeightDto::create(10, 'KILOGRAM');

// Get the conversion service
$conversionService = WeightConversionService::create($initialWeight);

// Convert to another unit
$convertedWeight = $conversionService->convert(WeightEnum::POUND);

echo $initialWeight . " is equal to " . $convertedWeight;
// Output: 10 KILOGRAM is equal to 22.0462 POUND

// You can also use the shortcut method on the DTO
echo $initialWeight->toKG(); // Converts to Kilogram (if not already)
// Output: 10 KILOGRAM
```

**Available Weight Units (`WeightEnum`):**

| Enum Case                      | String Value         |
| ------------------------------ | -------------------- |
| `WeightEnum::MILLIGRAM`        | `'MILLIGRAM'`        |
| `WeightEnum::GRAM`             | `'GRAM'`             |
| `WeightEnum::KILOGRAM`         | `'KILOGRAM'`         |
| `WeightEnum::OUNCE`            | `'OUNCE'`            |
| `WeightEnum::POUND`            | `'POUND'`            |
| `WeightEnum::TON`              | `'TON'`              |
| `WeightEnum::STONE`            | `'STONE'`            |
| `WeightEnum::MICROGRAM`        | `'MICROGRAM'`        |
| `WeightEnum::NANOGRAM`         | `'NANOGRAM'`         |
| `WeightEnum::HUNDREDTHS_POUND` | `'HUNDREDTHS_POUND'` |

### Dimension Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;

// Create a DimensionDto
$initialDimension = new DimensionDto(5, DimensionEnum::METER);
// Or using the static factory method
// $initialDimension = DimensionDto::create(5, 'METER');

// Get the conversion service
$conversionService = DimensionConversionService::create($initialDimension);

// Convert to another unit
$convertedDimension = $conversionService->convert(DimensionEnum::FOOT);

echo $initialDimension . " is equal to " . $convertedDimension;
// Output: 5 METER is equal to 16.4042 FOOT

// You can also use the shortcut method on the DTO
echo $initialDimension->toCM(); // Converts to Centimeter
// Output: 500 CENTIMETER
```

**Available Dimension Units (`DimensionEnum`):**

| Enum Case                   | String Value   |
| --------------------------- | -------------- |
| `DimensionEnum::INCH`       | `'INCH'`       |
| `DimensionEnum::CENTIMETER` | `'CENTIMETER'` |
| `DimensionEnum::METER`      | `'METER'`      |
| `DimensionEnum::KILOMETER`  | `'KILOMETER'`  |
| `DimensionEnum::FOOT`       | `'FOOT'`       |
| `DimensionEnum::YARD`       | `'YARD'`       |
| `DimensionEnum::MILE`       | `'MILE'`       |
| `DimensionEnum::MILLIMETER` | `'MILLIMETER'` |
| `DimensionEnum::MICROMETER` | `'MICROMETER'` |
| `DimensionEnum::NANOMETER`  | `'NANOMETER'`  |
| `DimensionEnum::DECIMETER`  | `'DECIMETER'`  |

### Liquid Volume Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\LiquidVolumeDto;
use Bramato\DimensionUtility\Enum\LiquidVolumeEnum;
use Bramato\DimensionUtility\Services\LiquidVolumeConversionService;

// Create a LiquidVolumeDto
$initialVolume = new LiquidVolumeDto(2, LiquidVolumeEnum::GAL);
// Or using the static factory method
// $initialVolume = LiquidVolumeDto::create(2, 'GAL');

// Get the conversion service
$conversionService = LiquidVolumeConversionService::create($initialVolume);

// Convert to another unit
$convertedVolume = $conversionService->convert(LiquidVolumeEnum::L);

echo $initialVolume . " is equal to " . $convertedVolume;
// Output: 2 GAL is equal to 7.57082 L

// You can also use the shortcut method on the DTO
echo $initialVolume->toLT(); // Converts to Liter
// Output: 7.57082 L
```

**Available Liquid Volume Units (`LiquidVolumeEnum`):**

| Enum Case                 | String Value |
| ------------------------- | ------------ |
| `LiquidVolumeEnum::ML`    | `'ML'`       |
| `LiquidVolumeEnum::L`     | `'L'`        |
| `LiquidVolumeEnum::FL_OZ` | `'FL_OZ'`    |
| `LiquidVolumeEnum::GAL`   | `'GAL'`      |
| `LiquidVolumeEnum::PT`    | `'PT'`       |
| `LiquidVolumeEnum::QT`    | `'QT'`       |
| `LiquidVolumeEnum::C`     | `'C'`        |

## Contributing

Contributions are welcome! Please feel free to submit a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. (Note: You might want to create a LICENSE.md file with the MIT license text).
