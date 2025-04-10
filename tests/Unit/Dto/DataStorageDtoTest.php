<?php

use Bramato\DimensionUtility\Dto\DataStorageDto;
use Bramato\DimensionUtility\Enum\DataStorageEnum;
use Bramato\DimensionUtility\Services\DataStorageConversionService; // Needed for helper methods

// Basic Instantiation and Property Access
test('can instantiate DataStorageDto and access properties', function () {
    $dto = new DataStorageDto(1024, DataStorageEnum::BYTE);
    expect($dto->value)->toBe(1024.0)
        ->and($dto->unit)->toBe(DataStorageEnum::BYTE);
});

// Static Factory Method 'create'
test('can create DataStorageDto using static factory method', function () {
    $dto = DataStorageDto::create(2, 'GIBIBYTE');
    expect($dto->value)->toBe(2.0)
        ->and($dto->unit)->toBe(DataStorageEnum::GIBIBYTE);
});

test('static factory method throws ValueError for invalid data storage unit', function () {
    DataStorageDto::create(10, 'INVALID_DATA_UNIT');
})->throws(ValueError::class);

// __toString Method
test('__toString returns correct format for data storage', function () {
    $dto = new DataStorageDto(512, DataStorageEnum::MEBIBYTE);
    expect((string) $dto)->toBe('512 MEBIBYTE');

    $dtoTiB = new DataStorageDto(1.5, DataStorageEnum::TEBIBYTE);
    expect((string) $dtoTiB)->toBe('1.5 TEBIBYTE');
});

// Helper Method toB
test('toB helper method converts data storage correctly', function () {
    $dtoKiB = new DataStorageDto(1, DataStorageEnum::KIBIBYTE);
    $converted = $dtoKiB->toB();
    expect($converted->value)->toBe(1024.0)
        ->and($converted->unit)->toBe(DataStorageEnum::BYTE);

    $dtoB = new DataStorageDto(500, DataStorageEnum::BYTE);
    $convertedSame = $dtoB->toB(); // Convert to self
    expect($convertedSame->value)->toBe(500.0)
        ->and($convertedSame->unit)->toBe(DataStorageEnum::BYTE);
});

// Helper Method toKiB
test('toKiB helper method converts data storage correctly', function () {
    $dtoMiB = new DataStorageDto(1, DataStorageEnum::MEBIBYTE);
    $converted = $dtoMiB->toKiB();
    expect($converted->value)->toBe(1024.0)
        ->and($converted->unit)->toBe(DataStorageEnum::KIBIBYTE);

    $dtoKiB = new DataStorageDto(2048, DataStorageEnum::KIBIBYTE);
    $convertedSame = $dtoKiB->toKiB(); // Convert to self
    expect($convertedSame->value)->toBe(2048.0)
        ->and($convertedSame->unit)->toBe(DataStorageEnum::KIBIBYTE);
});

// Helper Method toMiB
test('toMiB helper method converts data storage correctly', function () {
    $dtoGiB = new DataStorageDto(1, DataStorageEnum::GIBIBYTE);
    $converted = $dtoGiB->toMiB();
    expect($converted->value)->toBe(1024.0)
        ->and($converted->unit)->toBe(DataStorageEnum::MEBIBYTE);

    $dtoMiB = new DataStorageDto(512, DataStorageEnum::MEBIBYTE);
    $convertedSame = $dtoMiB->toMiB(); // Convert to self
    expect($convertedSame->value)->toBe(512.0)
        ->and($convertedSame->unit)->toBe(DataStorageEnum::MEBIBYTE);
});

// Helper Method toGiB
test('toGiB helper method converts data storage correctly', function () {
    $dtoTiB = new DataStorageDto(1, DataStorageEnum::TEBIBYTE);
    $converted = $dtoTiB->toGiB();
    expect($converted->value)->toBe(1024.0)
        ->and($converted->unit)->toBe(DataStorageEnum::GIBIBYTE);

    $dtoGiB = new DataStorageDto(2, DataStorageEnum::GIBIBYTE);
    $convertedSame = $dtoGiB->toGiB(); // Convert to self
    expect($convertedSame->value)->toBe(2.0)
        ->and($convertedSame->unit)->toBe(DataStorageEnum::GIBIBYTE);
});
