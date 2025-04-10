<?php

use Bramato\DimensionUtility\Dto\AreaDto;
use Bramato\DimensionUtility\Enum\AreaEnum;
use Bramato\DimensionUtility\Services\AreaConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate AreaDto and access properties', function () {
    $dto = new AreaDto(100.0, AreaEnum::SQ_METER);
    expect($dto->value)->toBe(100.0)
        ->and($dto->unit)->toBe(AreaEnum::SQ_METER);
});

// Static Factory Method 'create'
test('can create AreaDto using static factory method', function () {
    $dto = AreaDto::create(5, 'ACRE');
    expect($dto->value)->toBe(5.0)
        ->and($dto->unit)->toBe(AreaEnum::ACRE);
});

test('static factory method throws ValueError for invalid area unit', function () {
    AreaDto::create(10, 'INVALID_AREA_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for areas', function () {
    $dto = new AreaDto(1.5, AreaEnum::HECTARE);
    expect((string) $dto)->toBe('1.5 HECTARE');

    $dtoSqFt = new AreaDto(500, AreaEnum::SQ_FOOT);
    expect((string) $dtoSqFt)->toBe('500 SQ_FOOT');
});

// Helper Method toSQM
test('toSQM helper method converts areas correctly', function () {
    $dtoHa = new AreaDto(1, AreaEnum::HECTARE); // 1 ha = 10000 sqm
    $converted = $dtoHa->toSQM();
    $expected = 10000.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(AreaEnum::SQ_METER);

    $dtoSqm = new AreaDto(150, AreaEnum::SQ_METER);
    $convertedSame = $dtoSqm->toSQM(); // Convert to self
    expect($convertedSame->value)->toBe(150.0)
        ->and($convertedSame->unit)->toBe(AreaEnum::SQ_METER);
});

// Helper Method toACRE
test('toACRE helper method converts areas correctly', function () {
    $dtoSqm = new AreaDto(4046.8564224, AreaEnum::SQ_METER); // Approx 1 acre
    $converted = $dtoSqm->toACRE();
    $expected = 1.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(AreaEnum::ACRE);

    $dtoAcre = new AreaDto(2.5, AreaEnum::ACRE);
    $convertedSame = $dtoAcre->toACRE(); // Convert to self
    expect($convertedSame->value)->toBe(2.5)
        ->and($convertedSame->unit)->toBe(AreaEnum::ACRE);
});

// Helper Method toHECTARE
test('toHECTARE helper method converts areas correctly', function () {
    $dtoSqm = new AreaDto(20000, AreaEnum::SQ_METER); // 2 ha
    $converted = $dtoSqm->toHECTARE();
    $expected = 2.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expected - $delta, $expected + $delta)
        ->and($converted->unit)->toBe(AreaEnum::HECTARE);

    $dtoHa = new AreaDto(5, AreaEnum::HECTARE);
    $convertedSame = $dtoHa->toHECTARE(); // Convert to self
    expect($convertedSame->value)->toBe(5.0)
        ->and($convertedSame->unit)->toBe(AreaEnum::HECTARE);
});
