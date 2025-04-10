<?php

use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate DimensionDto and access properties', function () {
    $dto = new DimensionDto(2.5, DimensionEnum::METER);
    expect($dto->value)->toBe(2.5)
        ->and($dto->unit)->toBe(DimensionEnum::METER);
});

// Static Factory Method 'create'
test('can create DimensionDto using static factory method', function () {
    $dto = DimensionDto::create(100, 'CENTIMETER');
    expect($dto->value)->toBe(100.0)
        ->and($dto->unit)->toBe(DimensionEnum::CENTIMETER);
});

test('static factory method throws ValueError for invalid dimension unit', function () {
    DimensionDto::create(10, 'INVALID_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for dimensions', function () {
    $dto = new DimensionDto(50, DimensionEnum::INCH);
    expect((string) $dto)->toBe('50 INCH');

    $dtoM = new DimensionDto(1.8, DimensionEnum::METER);
    expect((string) $dtoM)->toBe('1.8 METER');
});

// Helper Method toCM
test('toCM helper method converts dimensions correctly', function () {
    $dtoM = new DimensionDto(1.5, DimensionEnum::METER);
    $converted = $dtoM->toCM();
    expect($converted->value)->toBe(150.0)
        ->and($converted->unit)->toBe(DimensionEnum::CENTIMETER);

    $dtoCm = new DimensionDto(200, DimensionEnum::CENTIMETER);
    $convertedSame = $dtoCm->toCM(); // Convert to self
    expect($convertedSame->value)->toBe(200.0)
        ->and($convertedSame->unit)->toBe(DimensionEnum::CENTIMETER);

    $dtoInch = new DimensionDto(10, DimensionEnum::INCH); // 1 inch = 2.54 cm
    $convertedInch = $dtoInch->toCM();
    $expectedCm = 25.4;
    $delta = 0.00001;
    expect($convertedInch->value)->toBeBetween($expectedCm - $delta, $expectedCm + $delta)
        ->and($convertedInch->unit)->toBe(DimensionEnum::CENTIMETER);
});

// Helper Method toM
test('toM helper method converts dimensions correctly', function () {
    $dtoCm = new DimensionDto(250, DimensionEnum::CENTIMETER);
    $converted = $dtoCm->toM();
    expect($converted->value)->toBe(2.5)
        ->and($converted->unit)->toBe(DimensionEnum::METER);

    $dtoM = new DimensionDto(3, DimensionEnum::METER);
    $convertedSame = $dtoM->toM(); // Convert to self
    expect($convertedSame->value)->toBe(3.0)
        ->and($convertedSame->unit)->toBe(DimensionEnum::METER);
});

// Helper Method toINCH
test('toINCH helper method converts dimensions correctly', function () {
    $dtoCm = new DimensionDto(25.4, DimensionEnum::CENTIMETER);
    $converted = $dtoCm->toINCH();
    $expectedInch = 10.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expectedInch - $delta, $expectedInch + $delta)
        ->and($converted->unit)->toBe(DimensionEnum::INCH);

    $dtoInch = new DimensionDto(12, DimensionEnum::INCH);
    $convertedSame = $dtoInch->toINCH(); // Convert to self
    expect($convertedSame->value)->toBe(12.0)
        ->and($convertedSame->unit)->toBe(DimensionEnum::INCH);
});
