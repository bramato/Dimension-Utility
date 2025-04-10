<?php

use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\WeightConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate WeightDto and access properties', function () {
    $dto = new WeightDto(10.5, WeightEnum::KILOGRAM);
    expect($dto->value)->toBe(10.5)
        ->and($dto->unit)->toBe(WeightEnum::KILOGRAM);
});

// Static Factory Method 'create'
test('can create WeightDto using static factory method', function () {
    $dto = WeightDto::create(5, 'GRAM');
    expect($dto->value)->toBe(5.0)
        ->and($dto->unit)->toBe(WeightEnum::GRAM);
});

test('static factory method throws ValueError for invalid unit', function () {
    WeightDto::create(10, 'INVALID_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format', function () {
    $dto = new WeightDto(15, WeightEnum::POUND);
    expect((string) $dto)->toBe('15 POUND');

    $dtoKg = new WeightDto(2.5, WeightEnum::KILOGRAM);
    expect((string) $dtoKg)->toBe('2.5 KILOGRAM');
});

// Helper Method toKG
test('toKG helper method converts correctly', function () {
    $dtoG = new WeightDto(1000, WeightEnum::GRAM);
    $converted = $dtoG->toKG();
    expect($converted->value)->toBe(1.0)
        ->and($converted->unit)->toBe(WeightEnum::KILOGRAM);

    $dtoKg = new WeightDto(5, WeightEnum::KILOGRAM);
    $convertedSame = $dtoKg->toKG(); // Convert to self
    expect($convertedSame->value)->toBe(5.0)
        ->and($convertedSame->unit)->toBe(WeightEnum::KILOGRAM);

    $dtoLb = new WeightDto(2.20462, WeightEnum::POUND); // Approx 1 KG
    $convertedLb = $dtoLb->toKG();
    $expectedKg = 1.0;
    $delta = 0.00001;
    expect($convertedLb->value)->toBeBetween($expectedKg - $delta, $expectedKg + $delta)
        ->and($convertedLb->unit)->toBe(WeightEnum::KILOGRAM);
});

// Helper Method toG
test('toG helper method converts correctly', function () {
    $dtoKg = new WeightDto(1.5, WeightEnum::KILOGRAM);
    $converted = $dtoKg->toG();
    expect($converted->value)->toBe(1500.0)
        ->and($converted->unit)->toBe(WeightEnum::GRAM);

    $dtoG = new WeightDto(500, WeightEnum::GRAM);
    $convertedSame = $dtoG->toG(); // Convert to self
    expect($convertedSame->value)->toBe(500.0)
        ->and($convertedSame->unit)->toBe(WeightEnum::GRAM);
});

// Helper Method toPOUND
test('toPOUND helper method converts correctly', function () {
    $dtoKg = new WeightDto(1, WeightEnum::KILOGRAM);
    $converted = $dtoKg->toPOUND();
    $expectedLb = 2.20462;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expectedLb - $delta, $expectedLb + $delta)
        ->and($converted->unit)->toBe(WeightEnum::POUND);

    $dtoLb = new WeightDto(10, WeightEnum::POUND);
    $convertedSame = $dtoLb->toPOUND(); // Convert to self
    expect($convertedSame->value)->toBe(10.0)
        ->and($convertedSame->unit)->toBe(WeightEnum::POUND);
});
