<?php

use Bramato\DimensionUtility\Dto\AreaDto;
use Bramato\DimensionUtility\Enum\AreaEnum;
use Bramato\DimensionUtility\Services\AreaConversionService;

// Test basic conversions
test('AreaConversionService converts SQ_METER to ACRE correctly', function () {
    $dto = new AreaDto(10000, AreaEnum::SQ_METER); // Approx 2.47 acres
    $service = AreaConversionService::create($dto);
    $converted = $service->convert(AreaEnum::ACRE);

    $expected = 2.4710538;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(AreaEnum::ACRE);
});

test('AreaConversionService converts HECTARE to SQ_FOOT correctly', function () {
    $dto = new AreaDto(1, AreaEnum::HECTARE); // 1 ha = 107639.1 sqft
    $service = AreaConversionService::create($dto);
    $converted = $service->convert(AreaEnum::SQ_FOOT);

    $expected = 107639.104167;
    $delta = 0.01; // Allow slightly larger delta
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(AreaEnum::SQ_FOOT);
});

test('AreaConversionService converts SQ_YARD to SQ_INCH correctly', function () {
    $dto = new AreaDto(1, AreaEnum::SQ_YARD); // 1 sqyd = 9 sqft = 1296 sqin
    $service = AreaConversionService::create($dto);
    $converted = $service->convert(AreaEnum::SQ_INCH);

    $expected = 1296.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(AreaEnum::SQ_INCH);
});


// Test conversion to self
test('AreaConversionService converts unit to itself correctly', function ($unit) {
    $value = 15.5;
    $dto = new AreaDto($value, $unit);
    $service = AreaConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(AreaEnum::cases()); // Test with all enum cases

// Test invalid conversion (skipped for now, assuming complete table)
/*
test('AreaConversionService throws exception for unsupported conversion', function () {
    // Mocking needed
})->throws(InvalidArgumentException::class);
*/
