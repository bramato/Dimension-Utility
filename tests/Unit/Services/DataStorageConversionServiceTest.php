<?php

use Bramato\DimensionUtility\Dto\DataStorageDto;
use Bramato\DimensionUtility\Enum\DataStorageEnum;
use Bramato\DimensionUtility\Services\DataStorageConversionService;

// Requires bcmath extension
if (!function_exists('bcadd')) {
    test('DataStorage tests require bcmath extension', function () {
        $this->markTestSkipped('BCMath extension not available.');
    });
    return; // Skip remaining tests if bcmath not present
}

// Test basic conversions
test('DataStorageConversionService converts KiB to MiB correctly', function () {
    $dto = new DataStorageDto(1024, DataStorageEnum::KIBIBYTE);
    $service = DataStorageConversionService::create($dto);
    $converted = $service->convert(DataStorageEnum::MEBIBYTE);
    expect($converted->value)->toBe(1.0)
        ->and($converted->unit)->toBe(DataStorageEnum::MEBIBYTE);
});

test('DataStorageConversionService converts GiB to Bytes correctly', function () {
    $dto = new DataStorageDto(1, DataStorageEnum::GIBIBYTE);
    $service = DataStorageConversionService::create($dto);
    $converted = $service->convert(DataStorageEnum::BYTE);
    expect($converted->value)->toBe(1073741824.0) // 1024 * 1024 * 1024
        ->and($converted->unit)->toBe(DataStorageEnum::BYTE);
});

test('DataStorageConversionService converts Bytes to TiB correctly', function () {
    // 1 TiB = 1024^4 Bytes
    $bytesInTib = 1099511627776.0;
    $dto = new DataStorageDto($bytesInTib, DataStorageEnum::BYTE);
    $service = DataStorageConversionService::create($dto);
    $converted = $service->convert(DataStorageEnum::TEBIBYTE);
    expect($converted->value)->toBe(1.0)
        ->and($converted->unit)->toBe(DataStorageEnum::TEBIBYTE);
});

test('DataStorageConversionService converts PiB to GiB correctly', function () {
    $dto = new DataStorageDto(1, DataStorageEnum::PEBIBYTE);
    $service = DataStorageConversionService::create($dto);
    $converted = $service->convert(DataStorageEnum::GIBIBYTE);
    expect($converted->value)->toBe(1048576.0) // 1024 * 1024
        ->and($converted->unit)->toBe(DataStorageEnum::GIBIBYTE);
});

// Test conversion to self
test('DataStorageConversionService converts unit to itself correctly', function ($unit) {
    $value = 2.0;
    $dto = new DataStorageDto($value, $unit);
    $service = DataStorageConversionService::create($dto);
    $converted = $service->convert($unit);
    expect($converted->value)->toBe($value)
        ->and($converted->unit)->toBe($unit);
})->with(DataStorageEnum::cases()); // Test with all enum cases

// Test invalid conversion (skipped)
/*
test('DataStorageConversionService throws exception for unsupported conversion', function () {
    // Mocking needed
})->throws(InvalidArgumentException::class);
*/
