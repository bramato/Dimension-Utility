<?php

use Bramato\DimensionUtility\Dto\LiquidVolumeDto;
use Bramato\DimensionUtility\Enum\LiquidVolumeEnum;
use Bramato\DimensionUtility\Services\LiquidVolumeConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate LiquidVolumeDto and access properties', function () {
    $dto = new LiquidVolumeDto(1.0, LiquidVolumeEnum::L);
    expect($dto->value)->toBe(1.0)
        ->and($dto->unit)->toBe(LiquidVolumeEnum::L);
});

// Static Factory Method 'create'
test('can create LiquidVolumeDto using static factory method', function () {
    $dto = LiquidVolumeDto::create(500, 'ML');
    expect($dto->value)->toBe(500.0)
        ->and($dto->unit)->toBe(LiquidVolumeEnum::ML);
});

test('static factory method throws ValueError for invalid liquid volume unit', function () {
    LiquidVolumeDto::create(10, 'INVALID_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for liquid volumes', function () {
    $dto = new LiquidVolumeDto(2, LiquidVolumeEnum::GAL);
    expect((string) $dto)->toBe('2 GAL');

    $dtoL = new LiquidVolumeDto(0.75, LiquidVolumeEnum::L);
    expect((string) $dtoL)->toBe('0.75 L');
});

// Helper Method toLT
test('toLT helper method converts liquid volumes correctly', function () {
    $dtoMl = new LiquidVolumeDto(1500, LiquidVolumeEnum::ML);
    $converted = $dtoMl->toLT();
    expect($converted->value)->toBe(1.5)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::L);

    $dtoL = new LiquidVolumeDto(3, LiquidVolumeEnum::L);
    $convertedSame = $dtoL->toLT(); // Convert to self
    expect($convertedSame->value)->toBe(3.0)
        ->and($convertedSame->unit)->toBe(LiquidVolumeEnum::L);

    $dtoGal = new LiquidVolumeDto(1, LiquidVolumeEnum::GAL); // 1 gal = 3.78541 L
    $convertedGal = $dtoGal->toLT();
    $expectedL = 3.78541;
    $delta = 0.00001;
    expect($convertedGal->value)->toBeBetween($expectedL - $delta, $expectedL + $delta)
        ->and($convertedGal->unit)->toBe(LiquidVolumeEnum::L);
});

// Helper Method toML
test('toML helper method converts liquid volumes correctly', function () {
    $dtoL = new LiquidVolumeDto(0.5, LiquidVolumeEnum::L);
    $converted = $dtoL->toML();
    expect($converted->value)->toBe(500.0)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::ML);

    $dtoMl = new LiquidVolumeDto(250, LiquidVolumeEnum::ML);
    $convertedSame = $dtoMl->toML(); // Convert to self
    expect($convertedSame->value)->toBe(250.0)
        ->and($convertedSame->unit)->toBe(LiquidVolumeEnum::ML);
});

// Helper Method toGAL
test('toGAL helper method converts liquid volumes correctly', function () {
    $dtoL = new LiquidVolumeDto(3.78541, LiquidVolumeEnum::L); // Approx 1 GAL
    $converted = $dtoL->toGAL();
    $expectedGal = 1.0;
    $delta = 0.00001;
    expect($converted->value)->toBeBetween($expectedGal - $delta, $expectedGal + $delta)
        ->and($converted->unit)->toBe(LiquidVolumeEnum::GAL);

    $dtoGal = new LiquidVolumeDto(5, LiquidVolumeEnum::GAL);
    $convertedSame = $dtoGal->toGAL(); // Convert to self
    expect($convertedSame->value)->toBe(5.0)
        ->and($convertedSame->unit)->toBe(LiquidVolumeEnum::GAL);
});
