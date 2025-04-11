<?php

use Bramato\DimensionUtility\Domain\Dto\ProductDto;
use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Dto\LiquidVolumeDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Enum\LiquidVolumeEnum;

test('can instantiate ProductDto and access properties', function () {
    $dimensions = new BoxDto(
        new DimensionDto(10, DimensionEnum::CENTIMETER),
        new DimensionDto(20, DimensionEnum::CENTIMETER),
        new DimensionDto(5, DimensionEnum::CENTIMETER)
    );
    $weight = new WeightDto(0.5, WeightEnum::KILOGRAM);
    $volume = new LiquidVolumeDto(250, LiquidVolumeEnum::ML);

    $product = new ProductDto(
        sku: 'SKU123',
        name: 'Test Product',
        dimensions: $dimensions,
        weight: $weight,
        liquidVolume: $volume
    );

    expect($product->sku)->toBe('SKU123')
        ->and($product->name)->toBe('Test Product')
        ->and($product->dimensions)->toBe($dimensions)
        ->and($product->weight)->toBe($weight)
        ->and($product->liquidVolume)->toBe($volume);
});

test('can instantiate ProductDto without liquid volume', function () {
    $dimensions = new BoxDto(
        new DimensionDto(5, DimensionEnum::INCH),
        new DimensionDto(5, DimensionEnum::INCH),
        new DimensionDto(10, DimensionEnum::INCH)
    );
    $weight = new WeightDto(2, WeightEnum::POUND);

    $product = new ProductDto(
        sku: 'SKU456',
        name: 'Solid Product',
        dimensions: $dimensions,
        weight: $weight
        // liquidVolume defaults to null
    );

    expect($product->sku)->toBe('SKU456')
        ->and($product->name)->toBe('Solid Product')
        ->and($product->dimensions)->toBe($dimensions)
        ->and($product->weight)->toBe($weight)
        ->and($product->liquidVolume)->toBeNull();
});

test('calculateDensity returns correct density in kg/m³ using metric units', function () {
    // Box: 0.1m x 0.2m x 0.05m = 0.001 m³
    $dimensions = new BoxDto(
        new DimensionDto(10, DimensionEnum::CENTIMETER),
        new DimensionDto(20, DimensionEnum::CENTIMETER),
        new DimensionDto(5, DimensionEnum::CENTIMETER)
    );
    // Weight: 0.5 kg
    $weight = new WeightDto(500, WeightEnum::GRAM);

    $product = new ProductDto('DENSE1', 'Dense Product', $dimensions, $weight);

    // Density = 0.5 kg / 0.001 m³ = 500 kg/m³
    $expectedDensity = 500.0;
    $delta = 0.00001;
    expect($product->calculateDensity())->toBeBetween($expectedDensity - $delta, $expectedDensity + $delta);
});

test('calculateDensity returns correct density in kg/m³ using mixed units', function () {
    // Box: 2 INCH = 0.0508m, 3 INCH = 0.0762m, 4 INCH = 0.1016m
    // Volume = 0.0508 * 0.0762 * 0.1016 = 0.000393289 m³ (approx)
    $dimensions = new BoxDto(
        new DimensionDto(2, DimensionEnum::INCH),
        new DimensionDto(3, DimensionEnum::INCH),
        new DimensionDto(4, DimensionEnum::INCH)
    );
    // Weight: 1 POUND = 0.453592 kg
    $weight = new WeightDto(1, WeightEnum::POUND);

    $product = new ProductDto('DENSE2', 'Dense Product Imperial', $dimensions, $weight);

    // Density = 0.453592 kg / 0.000393289 m³ = 1153.33 kg/m³ (approx)
    $expectedDensity = 1153.33;
    $delta = 0.01; // Larger delta due to potential float inaccuracies
    expect($product->calculateDensity())->toBeBetween($expectedDensity - $delta, $expectedDensity + $delta);
});

test('calculateDensity throws LogicException if volume is zero', function () {
    $dimensions = new BoxDto(
        new DimensionDto(10, DimensionEnum::CENTIMETER),
        new DimensionDto(0, DimensionEnum::CENTIMETER), // Zero width -> zero volume
        new DimensionDto(5, DimensionEnum::CENTIMETER)
    );
    $weight = new WeightDto(500, WeightEnum::GRAM);

    $product = new ProductDto('ZEROVOL', 'Zero Volume Product', $dimensions, $weight);

    expect(fn() => $product->calculateDensity())
        ->toThrow(\LogicException::class, 'Volume must be positive to calculate density.');
});

test('createMetric factory creates product with correct metric units', function () {
    $product = ProductDto::createMetric(
        sku: 'METRIC001',
        name: 'Metric Product',
        lengthCm: 30.5,
        widthCm: 20,
        heightCm: 10,
        weightKg: 1.2
    );

    expect($product->sku)->toBe('METRIC001')
        ->and($product->name)->toBe('Metric Product')
        ->and($product->dimensions->length->value)->toBe(30.5)
        ->and($product->dimensions->length->unit)->toBe(DimensionEnum::CENTIMETER)
        ->and($product->dimensions->width->value)->toBe(20.0)
        ->and($product->dimensions->width->unit)->toBe(DimensionEnum::CENTIMETER)
        ->and($product->dimensions->height->value)->toBe(10.0)
        ->and($product->dimensions->height->unit)->toBe(DimensionEnum::CENTIMETER)
        ->and($product->weight->value)->toBe(1.2)
        ->and($product->weight->unit)->toBe(WeightEnum::KILOGRAM)
        ->and($product->liquidVolume)->toBeNull();
});

test('createImperial factory creates product with correct imperial units', function () {
    $product = ProductDto::createImperial(
        sku: 'IMPERIAL001',
        name: 'Imperial Product',
        lengthInch: 12,
        widthInch: 8,
        heightInch: 4,
        weightPound: 2.5
    );

    expect($product->sku)->toBe('IMPERIAL001')
        ->and($product->name)->toBe('Imperial Product')
        ->and($product->dimensions->length->value)->toBe(12.0)
        ->and($product->dimensions->length->unit)->toBe(DimensionEnum::INCH)
        ->and($product->dimensions->width->value)->toBe(8.0)
        ->and($product->dimensions->width->unit)->toBe(DimensionEnum::INCH)
        ->and($product->dimensions->height->value)->toBe(4.0)
        ->and($product->dimensions->height->unit)->toBe(DimensionEnum::INCH)
        ->and($product->weight->value)->toBe(2.5)
        ->and($product->weight->unit)->toBe(WeightEnum::POUND)
        ->and($product->liquidVolume)->toBeNull();
});
