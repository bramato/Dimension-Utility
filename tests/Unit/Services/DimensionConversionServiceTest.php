<?php

use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;

// Test basic conversions
test('DimensionConversionService converts METER to FOOT correctly', function () {
    $dto = new DimensionDto(1, DimensionEnum::METER);
    $service = DimensionConversionService::create($dto);
    $converted = $service->convert(DimensionEnum::FOOT);

    $expected = 3.28084;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(DimensionEnum::FOOT);
});

test('DimensionConversionService converts INCH to MILLIMETER correctly', function () {
    $dto = new DimensionDto(1, DimensionEnum::INCH);
    $service = DimensionConversionService::create($dto);
    $converted = $service->convert(DimensionEnum::MILLIMETER);

    $expected = 25.4;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(DimensionEnum::MILLIMETER);
});

test('DimensionConversionService converts YARD to CENTIMETER correctly', function () {
    $dto = new DimensionDto(1, DimensionEnum::YARD);
    $service = DimensionConversionService::create($dto);
    $converted = $service->convert(DimensionEnum::CENTIMETER);

    $expected = 91.44;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(DimensionEnum::CENTIMETER);
});

test('DimensionConversionService converts MILE to KILOMETER correctly', function () {
    $dto = new DimensionDto(1, DimensionEnum::MILE);
    $service = DimensionConversionService::create($dto);
    $converted = $service->convert(DimensionEnum::KILOMETER);

    $expected = 1.60934;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(DimensionEnum::KILOMETER);
});

// Test conversion to self
test('DimensionConversionService converts unit to itself correctly', function ($unit) {
    $value = 5.0;
    $dto = new DimensionDto($value, $unit);
    $service = DimensionConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(DimensionEnum::cases()); // Test with all enum cases

// Test invalid conversion (skipped for now, assuming complete table)
/*
test('DimensionConversionService throws exception for unsupported conversion', function () {
    // Mocking needed
})->throws(InvalidArgumentException::class);
*/
