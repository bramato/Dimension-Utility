<?php

use Bramato\DimensionUtility\Dto\LiquidVolumeDto;
use Bramato\DimensionUtility\Enum\LiquidVolumeEnum;
use Bramato\DimensionUtility\Services\LiquidVolumeConversionService;

// Test basic conversions
test('LiquidVolumeConversionService converts L to GAL correctly', function () {
    $dto = new LiquidVolumeDto(1, LiquidVolumeEnum::L);
    $service = LiquidVolumeConversionService::create($dto);
    $converted = $service->convert(LiquidVolumeEnum::GAL);

    $expected = 0.264172;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::GAL);
});

test('LiquidVolumeConversionService converts GAL to ML correctly', function () {
    $dto = new LiquidVolumeDto(1, LiquidVolumeEnum::GAL);
    $service = LiquidVolumeConversionService::create($dto);
    $converted = $service->convert(LiquidVolumeEnum::ML);

    $expected = 3785.41;
    $delta = 0.01; // Slightly larger delta due to multiple conversions
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::ML);
});

test('LiquidVolumeConversionService converts FL_OZ to C correctly', function () {
    $dto = new LiquidVolumeDto(16, LiquidVolumeEnum::FL_OZ); // 1 cup = 8 fl oz
    $service = LiquidVolumeConversionService::create($dto);
    $converted = $service->convert(LiquidVolumeEnum::C);

    $expected = 2.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::C);
});

test('LiquidVolumeConversionService converts QT to PT correctly', function () {
    $dto = new LiquidVolumeDto(1, LiquidVolumeEnum::QT); // 1 qt = 2 pt
    $service = LiquidVolumeConversionService::create($dto);
    $converted = $service->convert(LiquidVolumeEnum::PT);

    $expected = 2.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::PT);
});

// Test conversion to self
test('LiquidVolumeConversionService converts unit to itself correctly', function ($unit) {
    $value = 1.0;
    $dto = new LiquidVolumeDto($value, $unit);
    $service = LiquidVolumeConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(LiquidVolumeEnum::cases()); // Test with all enum cases

// Test invalid conversion (skipped for now, assuming complete table)
/*
test('LiquidVolumeConversionService throws exception for unsupported conversion', function () {
    // Mocking needed
})->throws(InvalidArgumentException::class);
*/
