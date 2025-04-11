<?php

use Bramato\DimensionUtility\Dto\SpeedDto;
use Bramato\DimensionUtility\Enum\SpeedEnum;
use Bramato\DimensionUtility\Services\SpeedConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate SpeedDto and access properties', function () {
    $dto = new SpeedDto(10.0, SpeedEnum::METER_PER_SECOND);
    expect($dto->value)->toBe(10.0)
        ->and($dto->unit)->toBe(SpeedEnum::METER_PER_SECOND);
});

// Static Factory Method 'create'
test('can create SpeedDto using static factory method', function () {
    $dto = SpeedDto::create(60, 'MILE_PER_HOUR');
    expect($dto->value)->toBe(60.0)
        ->and($dto->unit)->toBe(SpeedEnum::MILE_PER_HOUR);
});

test('static factory method throws ValueError for invalid speed unit', function () {
    SpeedDto::create(10, 'INVALID_SPEED_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for speeds', function () {
    $dto = new SpeedDto(100, SpeedEnum::KILOMETER_PER_HOUR);
    expect((string) $dto)->toBe('100 KILOMETER_PER_HOUR');

    $dtoKnot = new SpeedDto(20, SpeedEnum::KNOT);
    expect((string) $dtoKnot)->toBe('20 KNOT');
});

// Helper Method toMPS
test('toMPS helper method converts speeds correctly', function () {
    $dtoKph = new SpeedDto(36, SpeedEnum::KILOMETER_PER_HOUR); // 10 m/s
    $convertedKph = $dtoKph->toMPS();
    $expected1 = 10.0;
    $delta1 = 0.00001;
    expect($convertedKph->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedKph->unit)->toBe(SpeedEnum::METER_PER_SECOND);

    $dtoMps = new SpeedDto(15, SpeedEnum::METER_PER_SECOND);
    $convertedSame = $dtoMps->toMPS(); // Convert to self
    expect($convertedSame->value)->toBe(15.0)
        ->and($convertedSame->unit)->toBe(SpeedEnum::METER_PER_SECOND);
});

// Helper Method toKPH
test('toKPH helper method converts speeds correctly', function () {
    $dtoMps = new SpeedDto(10, SpeedEnum::METER_PER_SECOND); // 36 km/h
    $convertedMps = $dtoMps->toKPH();
    $expected1 = 36.0;
    $delta1 = 0.00001;
    expect($convertedMps->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedMps->unit)->toBe(SpeedEnum::KILOMETER_PER_HOUR);

    $dtoKph = new SpeedDto(100, SpeedEnum::KILOMETER_PER_HOUR);
    $convertedSame = $dtoKph->toKPH(); // Convert to self
    expect($convertedSame->value)->toBe(100.0)
        ->and($convertedSame->unit)->toBe(SpeedEnum::KILOMETER_PER_HOUR);
});

// Helper Method toMPH
test('toMPH helper method converts speeds correctly', function () {
    $dtoMps = new SpeedDto(10, SpeedEnum::METER_PER_SECOND); // approx 22.37 mph
    $convertedMps = $dtoMps->toMPH();
    $expected1 = 22.36936;
    $delta1 = 0.00001;
    expect($convertedMps->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedMps->unit)->toBe(SpeedEnum::MILE_PER_HOUR);

    $dtoMph = new SpeedDto(70, SpeedEnum::MILE_PER_HOUR);
    $convertedSame = $dtoMph->toMPH(); // Convert to self
    expect($convertedSame->value)->toBe(70.0)
        ->and($convertedSame->unit)->toBe(SpeedEnum::MILE_PER_HOUR);
});
