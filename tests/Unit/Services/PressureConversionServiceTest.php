<?php

use Bramato\DimensionUtility\Dto\PressureDto;
use Bramato\DimensionUtility\Enum\PressureEnum;
use Bramato\DimensionUtility\Services\PressureConversionService;

// Test basic conversions
test('PressureConversionService converts BAR to PSI correctly', function () {
    $dto = new PressureDto(1, PressureEnum::BAR); // approx 14.5038 psi
    $service = PressureConversionService::create($dto);
    $converted = $service->convert(PressureEnum::PSI);
    $expected = 14.50377;
    $delta = 0.0001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(PressureEnum::PSI);
});

test('PressureConversionService converts PSI to KILOPASCAL correctly', function () {
    $dto = new PressureDto(1, PressureEnum::PSI); // approx 6.89476 kPa
    $service = PressureConversionService::create($dto);
    $converted = $service->convert(PressureEnum::KILOPASCAL);
    $expected = 6.894757;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(PressureEnum::KILOPASCAL);
});

test('PressureConversionService converts ATMOSPHERE to TORR correctly', function () {
    $dto = new PressureDto(1, PressureEnum::ATMOSPHERE); // 760 Torr
    $service = PressureConversionService::create($dto);
    $converted = $service->convert(PressureEnum::TORR);
    $expected = 760.0;
    $delta = 0.01; // Based on definition
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(PressureEnum::TORR);
});

test('PressureConversionService converts TORR to MILLIBAR correctly', function () {
    $dto = new PressureDto(750.06168, PressureEnum::TORR); // approx 1000 mbar (1 bar)
    $service = PressureConversionService::create($dto);
    $converted = $service->convert(PressureEnum::MILLIBAR);
    $expected = 1000.0;
    $delta = 0.0001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(PressureEnum::MILLIBAR);
});

// Test conversion to self
test('PressureConversionService converts unit to itself correctly', function ($unit) {
    $value = 10.0;
    $dto = new PressureDto($value, $unit);
    $service = PressureConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(PressureEnum::cases()); // Test with all enum cases

// Test invalid conversion (skipped)
/*
test('PressureConversionService throws exception for unsupported conversion', function () {
    // Mocking needed
})->throws(InvalidArgumentException::class);
*/
