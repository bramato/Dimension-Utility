<?php

use Bramato\DimensionUtility\Dto\SpeedDto;
use Bramato\DimensionUtility\Enum\SpeedEnum;
use Bramato\DimensionUtility\Services\SpeedConversionService;

// Test basic conversions
test('SpeedConversionService converts KPH to MPH correctly', function () {
    $dto = new SpeedDto(100, SpeedEnum::KILOMETER_PER_HOUR); // approx 62.137 mph
    $service = SpeedConversionService::create($dto);
    $converted = $service->convert(SpeedEnum::MILE_PER_HOUR);
    $expected = 62.1371;
    $delta = 0.0001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(SpeedEnum::MILE_PER_HOUR);
});

test('SpeedConversionService converts MPH to KNOT correctly', function () {
    $dto = new SpeedDto(10, SpeedEnum::MILE_PER_HOUR); // approx 8.68976 knots
    $service = SpeedConversionService::create($dto);
    $converted = $service->convert(SpeedEnum::KNOT);
    $expected = 8.68976;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(SpeedEnum::KNOT);
});

test('SpeedConversionService converts KNOT to FPS correctly', function () {
    $dto = new SpeedDto(1, SpeedEnum::KNOT); // 1 knot ≈ 1.68781 ft/s
    $service = SpeedConversionService::create($dto);
    $converted = $service->convert(SpeedEnum::FOOT_PER_SECOND);
    $expected = 1.68781;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(SpeedEnum::FOOT_PER_SECOND);
});


// Test conversion to self
test('SpeedConversionService converts unit to itself correctly', function ($unit) {
    $value = 50.0;
    $dto = new SpeedDto($value, $unit);
    $service = SpeedConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(SpeedEnum::cases()); // Test with all enum cases

// Test invalid conversion (skipped for now)
/*
test('SpeedConversionService throws exception for unsupported conversion', function () {
    // Mocking needed
})->throws(InvalidArgumentException::class);
*/
