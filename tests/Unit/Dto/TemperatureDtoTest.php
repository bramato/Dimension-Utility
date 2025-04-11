<?php

use Bramato\DimensionUtility\Dto\TemperatureDto;
use Bramato\DimensionUtility\Enum\TemperatureEnum;
use Bramato\DimensionUtility\Services\TemperatureConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate TemperatureDto and access properties', function () {
    $dto = new TemperatureDto(25.0, TemperatureEnum::CELSIUS);
    expect($dto->value)->toBe(25.0)
        ->and($dto->unit)->toBe(TemperatureEnum::CELSIUS);
});

// Static Factory Method 'create'
test('can create TemperatureDto using static factory method', function () {
    $dto = TemperatureDto::create(77, 'FAHRENHEIT');
    expect($dto->value)->toBe(77.0)
        ->and($dto->unit)->toBe(TemperatureEnum::FAHRENHEIT);
});

test('static factory method throws ValueError for invalid temperature unit', function () {
    TemperatureDto::create(10, 'INVALID_TEMP_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for temperatures', function () {
    $dto = new TemperatureDto(0, TemperatureEnum::CELSIUS);
    expect((string) $dto)->toBe('0 CELSIUS');

    $dtoK = new TemperatureDto(273.15, TemperatureEnum::KELVIN);
    expect((string) $dtoK)->toBe('273.15 KELVIN');
});

// Helper Method toC
test('toC helper method converts temperatures correctly', function () {
    $dtoF = new TemperatureDto(32, TemperatureEnum::FAHRENHEIT); // 0 C
    $convertedF = $dtoF->toC();
    $expectedC1 = 0.0;
    $delta1 = 0.00001;
    expect($convertedF->value)->toBeBetween($expectedC1 - $delta1, $expectedC1 + $delta1)
        ->and($convertedF->unit)->toBe(TemperatureEnum::CELSIUS);

    $dtoK = new TemperatureDto(273.15, TemperatureEnum::KELVIN); // 0 C
    $convertedK = $dtoK->toC();
    $expectedC2 = 0.0;
    $delta2 = 0.00001;
    expect($convertedK->value)->toBeBetween($expectedC2 - $delta2, $expectedC2 + $delta2)
        ->and($convertedK->unit)->toBe(TemperatureEnum::CELSIUS);

    $dtoC = new TemperatureDto(100, TemperatureEnum::CELSIUS);
    $convertedSame = $dtoC->toC(); // Convert to self
    expect($convertedSame->value)->toBe(100.0)
        ->and($convertedSame->unit)->toBe(TemperatureEnum::CELSIUS);
});

// Helper Method toF
test('toF helper method converts temperatures correctly', function () {
    $dtoC = new TemperatureDto(0, TemperatureEnum::CELSIUS); // 32 F
    $convertedC = $dtoC->toF();
    $expectedF1 = 32.0;
    $delta1 = 0.00001;
    expect($convertedC->value)->toBeBetween($expectedF1 - $delta1, $expectedF1 + $delta1)
        ->and($convertedC->unit)->toBe(TemperatureEnum::FAHRENHEIT);

    $dtoK = new TemperatureDto(0, TemperatureEnum::KELVIN); // -459.67 F
    $convertedK = $dtoK->toF();
    $expectedF2 = -459.67;
    $delta2 = 0.01;
    expect($convertedK->value)->toBeBetween($expectedF2 - $delta2, $expectedF2 + $delta2)
        ->and($convertedK->unit)->toBe(TemperatureEnum::FAHRENHEIT);

    $dtoF = new TemperatureDto(212, TemperatureEnum::FAHRENHEIT);
    $convertedSame = $dtoF->toF(); // Convert to self
    expect($convertedSame->value)->toBe(212.0)
        ->and($convertedSame->unit)->toBe(TemperatureEnum::FAHRENHEIT);
});

// Helper Method toK
test('toK helper method converts temperatures correctly', function () {
    $dtoC = new TemperatureDto(0, TemperatureEnum::CELSIUS); // 273.15 K
    $convertedC = $dtoC->toK();
    $expectedK1 = 273.15;
    $delta1 = 0.00001;
    expect($convertedC->value)->toBeBetween($expectedK1 - $delta1, $expectedK1 + $delta1)
        ->and($convertedC->unit)->toBe(TemperatureEnum::KELVIN);

    $dtoF = new TemperatureDto(32, TemperatureEnum::FAHRENHEIT); // 273.15 K
    $convertedF = $dtoF->toK();
    $expectedK2 = 273.15;
    $delta2 = 0.00001;
    expect($convertedF->value)->toBeBetween($expectedK2 - $delta2, $expectedK2 + $delta2)
        ->and($convertedF->unit)->toBe(TemperatureEnum::KELVIN);

    $dtoK = new TemperatureDto(100, TemperatureEnum::KELVIN);
    $convertedSame = $dtoK->toK(); // Convert to self
    expect($convertedSame->value)->toBe(100.0)
        ->and($convertedSame->unit)->toBe(TemperatureEnum::KELVIN);
});
