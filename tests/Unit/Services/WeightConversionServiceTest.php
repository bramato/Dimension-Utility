<?php

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\WeightConversionService;

// Test basic conversions
test('WeightConversionService converts KG to POUND correctly', function () {
    $dto = new WeightDto(1, WeightEnum::KILOGRAM);
    $service = WeightConversionService::create($dto);
    $converted = $service->convert(WeightEnum::POUND);

    $expected = 2.20462;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(WeightEnum::POUND);
});

test('WeightConversionService converts POUND to GRAM correctly', function () {
    $dto = new WeightDto(1, WeightEnum::POUND);
    $service = WeightConversionService::create($dto);
    $converted = $service->convert(WeightEnum::GRAM);

    $expected = 453.592;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(WeightEnum::GRAM);
});

test('WeightConversionService converts OUNCE to KILOGRAM correctly', function () {
    $dto = new WeightDto(16, WeightEnum::OUNCE); // Approx 1 pound = 0.453592 kg
    $service = WeightConversionService::create($dto);
    $converted = $service->convert(WeightEnum::KILOGRAM);

    $expected = 0.453592;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(WeightEnum::KILOGRAM);
});

test('WeightConversionService converts STONE to HUNDREDTHS_POUND correctly', function () {
    $dto = new WeightDto(2, WeightEnum::STONE); // 1 stone = 14 pounds = 1400 hundredths
    $service = WeightConversionService::create($dto);
    $converted = $service->convert(WeightEnum::HUNDREDTHS_POUND);

    $expected = 2800.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(WeightEnum::HUNDREDTHS_POUND);
});

// Test conversion to self
test('WeightConversionService converts unit to itself correctly', function ($unit) {
    $value = 10.0;
    $dto = new WeightDto($value, $unit);
    $service = WeightConversionService::create($dto);
    $converted = $service->convert($unit);

    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(WeightEnum::cases()); // Test with all enum cases

// Test invalid conversion (though table is complete, good practice)
// We need a hypothetical scenario where a conversion isn't defined.
// Since direct modification of the const table isn't feasible in a test,
// we skip testing the exception scenario for now, assuming the table is always complete.
// If the service logic changes to dynamically fetch factors, this test would be vital.
/*
test('WeightConversionService throws exception for unsupported conversion', function () {
    // This requires mocking or a way to simulate an incomplete CONVERSION_TABLE
    // For now, we assume the table covers all enum combinations.
})->throws(InvalidArgumentException::class);
*/
