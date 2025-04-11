![3aLvKlf.th.png](https://iili.io/3aLvKlf.th.png)

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

### Area Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\AreaDto;
use Bramato\DimensionUtility\Enum\AreaEnum;
use Bramato\DimensionUtility\Services\AreaConversionService;

// Create an AreaDto
$initialArea = new AreaDto(1.5, AreaEnum::HECTARE);
// Or using the static factory method
// $initialArea = AreaDto::create(1.5, 'HECTARE');

// Get the conversion service
$conversionService = AreaConversionService::create($initialArea);

// Convert to another unit
$convertedArea = $conversionService->convert(AreaEnum::ACRE);

echo $initialArea . " is equal to " . $convertedArea;
// Output: 1.5 HECTARE is equal to 3.706580721 ACRE (approx)

// You can also use the shortcut methods on the DTO
echo $initialArea->toSQM(); // Converts to Square Meters
// Output: 15000 SQ_METER
echo $initialArea->toACRE(); // Converts to Acres
// Output: 3.706580721 ACRE (approx)
```

**Available Area Units (`AreaEnum`):**

| Enum Case                 | String Value      |
| ------------------------- | ----------------- |
| `AreaEnum::SQ_METER`      | `'SQ_METER'`      |
| `AreaEnum::SQ_KILOMETER`  | `'SQ_KILOMETER'`  |
| `AreaEnum::SQ_CENTIMETER` | `'SQ_CENTIMETER'` |
| `AreaEnum::SQ_MILLIMETER` | `'SQ_MILLIMETER'` |
| `AreaEnum::SQ_FOOT`       | `'SQ_FOOT'`       |
| `AreaEnum::SQ_YARD`       | `'SQ_YARD'`       |
| `AreaEnum::SQ_INCH`       | `'SQ_INCH'`       |
| `AreaEnum::SQ_MILE`       | `'SQ_MILE'`       |
| `AreaEnum::ACRE`          | `'ACRE'`          |
| `AreaEnum::HECTARE`       | `'HECTARE'`       |

### Temperature Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\TemperatureDto;
use Bramato\DimensionUtility\Enum\TemperatureEnum;
use Bramato\DimensionUtility\Services\TemperatureConversionService;

// Create a TemperatureDto
$initialTemp = new TemperatureDto(25, TemperatureEnum::CELSIUS);
// Or using the static factory method
// $initialTemp = TemperatureDto::create(25, 'CELSIUS');

// Get the conversion service
$conversionService = TemperatureConversionService::create($initialTemp);

// Convert to another unit
$convertedTemp = $conversionService->convert(TemperatureEnum::FAHRENHEIT);

echo $initialTemp . " is equal to " . $convertedTemp;
// Output: 25 CELSIUS is equal to 77 FAHRENHEIT

// You can also use the shortcut methods on the DTO
echo $initialTemp->toF(); // Converts to Fahrenheit
// Output: 77 FAHRENHEIT
echo $initialTemp->toK(); // Converts to Kelvin
// Output: 298.15 KELVIN
```

**Available Temperature Units (`TemperatureEnum`):**

| Enum Case                     | String Value   |
| ----------------------------- | -------------- |
| `TemperatureEnum::CELSIUS`    | `'CELSIUS'`    |
| `TemperatureEnum::FAHRENHEIT` | `'FAHRENHEIT'` |
| `TemperatureEnum::KELVIN`     | `'KELVIN'`     |

### Speed Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\SpeedDto;
use Bramato\DimensionUtility\Enum\SpeedEnum;
use Bramato\DimensionUtility\Services\SpeedConversionService;

// Create a SpeedDto
$initialSpeed = new SpeedDto(100, SpeedEnum::KILOMETER_PER_HOUR);
// Or using the static factory method
// $initialSpeed = SpeedDto::create(100, 'KILOMETER_PER_HOUR');

// Get the conversion service
$conversionService = SpeedConversionService::create($initialSpeed);

// Convert to another unit
$convertedSpeed = $conversionService->convert(SpeedEnum::MILE_PER_HOUR);

echo $initialSpeed . " is equal to " . $convertedSpeed;
// Output: 100 KILOMETER_PER_HOUR is equal to 62.1371 MILE_PER_HOUR (approx)

// You can also use the shortcut methods on the DTO
echo $initialSpeed->toMPS(); // Converts to Meters per Second
// Output: 27.777... METER_PER_SECOND
echo $initialSpeed->toMPH(); // Converts to Miles per Hour
// Output: 62.1371 MILE_PER_HOUR (approx)
```

**Available Speed Units (`SpeedEnum`):**

| Enum Case                       | String Value           |
| ------------------------------- | ---------------------- |
| `SpeedEnum::METER_PER_SECOND`   | `'METER_PER_SECOND'`   |
| `SpeedEnum::KILOMETER_PER_HOUR` | `'KILOMETER_PER_HOUR'` |
| `SpeedEnum::MILE_PER_HOUR`      | `'MILE_PER_HOUR'`      |
| `SpeedEnum::KNOT`               | `'KNOT'`               |
| `SpeedEnum::FOOT_PER_SECOND`    | `'FOOT_PER_SECOND'`    |

### Data Storage Conversion

**Note:** This conversion service uses the BCMath PHP extension for arbitrary precision math, as data storage values can become very large. Ensure the `bcmath` extension is enabled in your PHP environment.

```php
<?php

use Bramato\DimensionUtility\Dto\DataStorageDto;
use Bramato\DimensionUtility\Enum\DataStorageEnum;
use Bramato\DimensionUtility\Services\DataStorageConversionService;

// Create a DataStorageDto
$initialStorage = new DataStorageDto(512, DataStorageEnum::MEBIBYTE);
// Or using the static factory method
// $initialStorage = DataStorageDto::create(512, 'MEBIBYTE');

// Get the conversion service
$conversionService = DataStorageConversionService::create($initialStorage);

// Convert to another unit
$convertedStorage = $conversionService->convert(DataStorageEnum::GIBIBYTE);

echo $initialStorage . " is equal to " . $convertedStorage;
// Output: 512 MEBIBYTE is equal to 0.5 GIBIBYTE

// You can also use the shortcut methods on the DTO
echo $initialStorage->toKiB(); // Converts to Kibibytes
// Output: 524288 KIBIBYTE
echo $initialStorage->toGiB(); // Converts to Gibibytes
// Output: 0.5 GIBIBYTE
```

**Available Data Storage Units (`DataStorageEnum` - Binary Prefixes):**

| Enum Case                   | String Value |
| --------------------------- | ------------ |
| `DataStorageEnum::BYTE`     | `'BYTE'`     |
| `DataStorageEnum::KIBIBYTE` | `'KIBIBYTE'` |
| `DataStorageEnum::MEBIBYTE` | `'MEBIBYTE'` |
| `DataStorageEnum::GIBIBYTE` | `'GIBIBYTE'` |
| `DataStorageEnum::TEBIBYTE` | `'TEBIBYTE'` |
| `DataStorageEnum::PEBIBYTE` | `'PEBIBYTE'` |

### Pressure Conversion

```php
<?php

use Bramato\DimensionUtility\Dto\PressureDto;
use Bramato\DimensionUtility\Enum\PressureEnum;
use Bramato\DimensionUtility\Services\PressureConversionService;

// Create a PressureDto
$initialPressure = new PressureDto(1, PressureEnum::BAR);
// Or using the static factory method
// $initialPressure = PressureDto::create(1, 'BAR');

// Get the conversion service
$conversionService = PressureConversionService::create($initialPressure);

// Convert to another unit
$convertedPressure = $conversionService->convert(PressureEnum::PSI);

echo $initialPressure . " is equal to " . $convertedPressure;
// Output: 1 BAR is equal to 14.50377 PSI (approx)

// You can also use the shortcut methods on the DTO
echo $initialPressure->toPa(); // Converts to Pascals
// Output: 100000 PASCAL
echo $initialPressure->toPsi(); // Converts to PSI
// Output: 14.50377 PSI (approx)
```

**Available Pressure Units (`PressureEnum`):**

| Enum Case                  | String Value   |
| -------------------------- | -------------- |
| `PressureEnum::PASCAL`     | `'PASCAL'`     |
| `PressureEnum::KILOPASCAL` | `'KILOPASCAL'` |
| `PressureEnum::MEGAPASCAL` | `'MEGAPASCAL'` |
| `PressureEnum::BAR`        | `'BAR'`        |
| `PressureEnum::MILLIBAR`   | `'MILLIBAR'`   |
| `PressureEnum::PSI`        | `'PSI'`        |
| `PressureEnum::ATMOSPHERE` | `'ATMOSPHERE'` |
| `PressureEnum::TORR`       | `'TORR'`       |

## Testing

This package uses Pest for unit testing. To run the tests, follow these steps:

1.  Ensure you have installed the development dependencies:
    ```bash
    composer install
    ```
2.  Run the Pest test suite:
    ```bash
    ./vendor/bin/pest
    ```

## Contributing

Contributions are welcome! Please feel free to submit a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. (Note: You might want to create a LICENSE.md file with the MIT license text).
