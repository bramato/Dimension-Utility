<?php

use Bramato\DimensionUtility\Dto\PressureDto;
use Bramato\DimensionUtility\Enum\PressureEnum;
use Bramato\DimensionUtility\Services\PressureConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate PressureDto and access properties', function () {
    $dto = new PressureDto(101325, PressureEnum::PASCAL);
    expect($dto->value)->toBe(101325.0)
        ->and($dto->unit)->toBe(PressureEnum::PASCAL);
});

// Static Factory Method 'create'
test('can create PressureDto using static factory method', function () {
    $dto = PressureDto::create(14.7, 'PSI');
    expect($dto->value)->toBe(14.7)
        ->and($dto->unit)->toBe(PressureEnum::PSI);
});

test('static factory method throws ValueError for invalid pressure unit', function () {
    PressureDto::create(10, 'INVALID_PRESSURE_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for pressures', function () {
    $dto = new PressureDto(1.0, PressureEnum::BAR);
    expect((string) $dto)->toBe('1 BAR');

    $dtoAtm = new PressureDto(1, PressureEnum::ATMOSPHERE);
    expect((string) $dtoAtm)->toBe('1 ATMOSPHERE');
});

// Helper Method toPa
test('toPa helper method converts pressures correctly', function () {
    $dtoBar = new PressureDto(1, PressureEnum::BAR); // 1 bar = 100000 Pa
    $convertedBar = $dtoBar->toPa();
    $expected1 = 100000.0;
    $delta1 = 0.00001;
    expect($convertedBar->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedBar->unit)->toBe(PressureEnum::PASCAL);

    $dtoPa = new PressureDto(50000, PressureEnum::PASCAL);
    $convertedSame = $dtoPa->toPa(); // Convert to self
    expect($convertedSame->value)->toBe(50000.0)
        ->and($convertedSame->unit)->toBe(PressureEnum::PASCAL);
});

// Helper Method toKPa
test('toKPa helper method converts pressures correctly', function () {
    $dtoPa = new PressureDto(1000, PressureEnum::PASCAL); // 1 kPa
    $convertedPa = $dtoPa->toKPa();
    $expected1 = 1.0;
    $delta1 = 0.00001;
    expect($convertedPa->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedPa->unit)->toBe(PressureEnum::KILOPASCAL);

    $dtoKPa = new PressureDto(150, PressureEnum::KILOPASCAL);
    $convertedSame = $dtoKPa->toKPa(); // Convert to self
    expect($convertedSame->value)->toBe(150.0)
        ->and($convertedSame->unit)->toBe(PressureEnum::KILOPASCAL);
});

// Helper Method toBar
test('toBar helper method converts pressures correctly', function () {
    $dtoPa = new PressureDto(100000, PressureEnum::PASCAL); // 1 bar
    $convertedPa = $dtoPa->toBar();
    $expected1 = 1.0;
    $delta1 = 0.00001;
    expect($convertedPa->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedPa->unit)->toBe(PressureEnum::BAR);

    $dtoBar = new PressureDto(2.5, PressureEnum::BAR);
    $convertedSame = $dtoBar->toBar(); // Convert to self
    expect($convertedSame->value)->toBe(2.5)
        ->and($convertedSame->unit)->toBe(PressureEnum::BAR);
});

// Helper Method toPsi
test('toPsi helper method converts pressures correctly', function () {
    $dtoPa = new PressureDto(6894.757, PressureEnum::PASCAL); // approx 1 PSI
    $convertedPa = $dtoPa->toPsi();
    $expected1 = 1.0;
    $delta1 = 0.00001;
    expect($convertedPa->value)->toBeBetween($expected1 - $delta1, $expected1 + $delta1)
        ->and($convertedPa->unit)->toBe(PressureEnum::PSI);

    $dtoPsi = new PressureDto(30, PressureEnum::PSI);
    $convertedSame = $dtoPsi->toPsi(); // Convert to self
    expect($convertedSame->value)->toBe(30.0)
        ->and($convertedSame->unit)->toBe(PressureEnum::PSI);
});
