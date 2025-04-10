<?php

use Bramato\DimensionUtility\Dto\TemperatureDto;
use Bramato\DimensionUtility\Enum\TemperatureEnum;
use Bramato\DimensionUtility\Services\TemperatureConversionService;

// Test basic conversions
test('TemperatureConversionService converts CELSIUS to FAHRENHEIT correctly', function () {
    $dto = new TemperatureDto(0, TemperatureEnum::CELSIUS);
    $service = TemperatureConversionService::create($dto);
    $converted = $service->convert(TemperatureEnum::FAHRENHEIT);
    $expected1 = 32.0;
    $delta1 = 0.00001;
    expect($converted->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($converted->unit)->toBe(TemperatureEnum::FAHRENHEIT);

    $dtoBoil = new TemperatureDto(100, TemperatureEnum::CELSIUS);
    $serviceBoil = TemperatureConversionService::create($dtoBoil);
    $convertedBoil = $serviceBoil->convert(TemperatureEnum::FAHRENHEIT);
    $expected2 = 212.0;
    $delta2 = 0.00001;
    expect($convertedBoil->value)->toBeBetween($expected2 - $delta2, $expected2 + $delta2)
        ->and($convertedBoil->unit)->toBe(TemperatureEnum::FAHRENHEIT);
});

test('TemperatureConversionService converts FAHRENHEIT to KELVIN correctly', function () {
    $dto = new TemperatureDto(32, TemperatureEnum::FAHRENHEIT); // 0 C = 273.15 K
    $service = TemperatureConversionService::create($dto);
    $converted = $service->convert(TemperatureEnum::KELVIN);
    $expected1 = 273.15;
    $delta1 = 0.00001;
    expect($converted->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($converted->unit)->toBe(TemperatureEnum::KELVIN);

    $dtoAbsoluteZeroF = new TemperatureDto(-459.67, TemperatureEnum::FAHRENHEIT); // 0 K
    $serviceZero = TemperatureConversionService::create($dtoAbsoluteZeroF);
    $convertedZero = $serviceZero->convert(TemperatureEnum::KELVIN);
    $expected2 = 0.0;
    $delta2 = 0.01; // Allow slightly larger delta
    expect($convertedZero->value)->toBeBetween($expected2 - $delta2, $expected2 + $delta2)
        ->and($convertedZero->unit)->toBe(TemperatureEnum::KELVIN);
});

test('TemperatureConversionService converts KELVIN to CELSIUS correctly', function () {
    $dto = new TemperatureDto(273.15, TemperatureEnum::KELVIN); // 0 C
    $service = TemperatureConversionService::create($dto);
    $converted = $service->convert(TemperatureEnum::CELSIUS);
    $expected1 = 0.0;
    $delta1 = 0.00001;
    expect($converted->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($converted->unit)->toBe(TemperatureEnum::CELSIUS);

    $dtoWarm = new TemperatureDto(300, TemperatureEnum::KELVIN);
    $serviceWarm = TemperatureConversionService::create($dtoWarm);
    $convertedWarm = $serviceWarm->convert(TemperatureEnum::CELSIUS);
    $expected2 = 26.85;
    $delta2 = 0.00001;
    expect($convertedWarm->value)->toBeBetween($expected2 - $delta2, $expected2 + $delta2)
        ->and($convertedWarm->unit)->toBe(TemperatureEnum::CELSIUS);
});

// Test conversion to self
test('TemperatureConversionService converts unit to itself correctly', function ($unit) {
    $value = 20.0;
    $dto = new TemperatureDto($value, $unit);
    $service = TemperatureConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(TemperatureEnum::cases()); // Test with all enum cases 