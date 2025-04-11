<?php

use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;

// Test Constructor and Properties
test('can instantiate BoxDto and access properties', function () {
    $length = new DimensionDto(10, DimensionEnum::CENTIMETER);
    $width = new DimensionDto(20, DimensionEnum::CENTIMETER);
    $height = new DimensionDto(5, DimensionEnum::CENTIMETER);

    $box = new BoxDto($length, $width, $height);

    expect($box->length)->toBe($length)
        ->and($box->width)->toBe($width)
        ->and($box->height)->toBe($height);
});

// Test calculateVolume
test('calculateVolume returns correct volume in base unit (cubic meters)', function () {
    $length = new DimensionDto(2, DimensionEnum::METER);
    $width = new DimensionDto(1, DimensionEnum::METER);
    $height = new DimensionDto(0.5, DimensionEnum::METER);
    $box = new BoxDto($length, $width, $height);

    // Default base unit is METER
    expect($box->calculateVolume())->toBe(1.0); // 2 * 1 * 0.5
});

test('calculateVolume handles different input units', function () {
    $length = new DimensionDto(200, DimensionEnum::CENTIMETER); // 2m
    $width = new DimensionDto(1, DimensionEnum::METER);         // 1m
    $height = new DimensionDto(500, DimensionEnum::MILLIMETER); // 0.5m
    $box = new BoxDto($length, $width, $height);

    // Default base unit is METER
    $expectedVolume = 1.0;
    $delta = 0.00001;
    expect($box->calculateVolume())->toBeBetween($expectedVolume - $delta, $expectedVolume + $delta); // 2 * 1 * 0.5
});

// Test calculateSurfaceArea
test('calculateSurfaceArea returns correct area in base unit (square meters)', function () {
    $length = new DimensionDto(2, DimensionEnum::METER);
    $width = new DimensionDto(1, DimensionEnum::METER);
    $height = new DimensionDto(3, DimensionEnum::METER);
    $box = new BoxDto($length, $width, $height);

    // Default base unit is METER. Area = 2 * (lw + lh + wh) = 2 * (2*1 + 2*3 + 1*3) = 2 * (2 + 6 + 3) = 2 * 11 = 22
    $expectedArea = 22.0;
    $delta = 0.00001;
    expect($box->calculateSurfaceArea())->toBeBetween($expectedArea - $delta, $expectedArea + $delta);
});

test('calculateSurfaceArea handles different input units', function () {
    $length = new DimensionDto(200, DimensionEnum::CENTIMETER); // 2m
    $width = new DimensionDto(1000, DimensionEnum::MILLIMETER); // 1m
    $height = new DimensionDto(3, DimensionEnum::METER);         // 3m
    $box = new BoxDto($length, $width, $height);

    // Default base unit is METER. Area = 2 * (2*1 + 2*3 + 1*3) = 22
    $expectedArea = 22.0;
    $delta = 0.00001;
    expect($box->calculateSurfaceArea())->toBeBetween($expectedArea - $delta, $expectedArea + $delta);
});

// Test getMaxDimension
test('getMaxDimension returns the longest dimension DTO', function () {
    $l = new DimensionDto(10, DimensionEnum::CENTIMETER);
    $w = new DimensionDto(0.5, DimensionEnum::METER); // 50 cm
    $h = new DimensionDto(400, DimensionEnum::MILLIMETER); // 40 cm
    $box = new BoxDto($l, $w, $h);

    expect($box->getMaxDimension())->toBe($w); // Width (0.5m) is the largest
});

test('getMaxDimension can convert result to specified unit', function () {
    $l = new DimensionDto(1, DimensionEnum::METER);
    $w = new DimensionDto(150, DimensionEnum::CENTIMETER); // 1.5m
    $h = new DimensionDto(1200, DimensionEnum::MILLIMETER); // 1.2m
    $box = new BoxDto($l, $w, $h);

    $maxDimInCm = $box->getMaxDimension(DimensionEnum::CENTIMETER);
    $expectedCm = 150.0;
    $delta = 0.00001;
    expect($maxDimInCm->value)->toBeBetween($expectedCm - $delta, $expectedCm + $delta)
        ->and($maxDimInCm->unit)->toBe(DimensionEnum::CENTIMETER);

    $maxDimInMm = $box->getMaxDimension(DimensionEnum::MILLIMETER);
    $expectedMm = 1500.0;
    expect($maxDimInMm->value)->toBeBetween($expectedMm - $delta, $expectedMm + $delta)
        ->and($maxDimInMm->unit)->toBe(DimensionEnum::MILLIMETER);
});
