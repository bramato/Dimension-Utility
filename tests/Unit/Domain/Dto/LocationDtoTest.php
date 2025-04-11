<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Dto;

use Bramato\DimensionUtility\Domain\Dto\LocationDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(LocationDto::class)]
class LocationDtoTest extends TestCase
{
    public function test_can_be_instantiated_with_required_coordinates(): void
    {
        $latitude = 45.4642;
        $longitude = 9.1900;

        $dto = new LocationDto(
            latitude: $latitude,
            longitude: $longitude
        );

        $this->assertSame($latitude, $dto->latitude);
        $this->assertSame($longitude, $dto->longitude);
        $this->assertNull($dto->altitude);
    }

    public function test_can_be_instantiated_with_all_properties(): void
    {
        $latitude = -33.8688;
        $longitude = 151.2093;
        $altitude = new DimensionDto(58.0, DimensionEnum::METER);

        $dto = new LocationDto(
            latitude: $latitude,
            longitude: $longitude,
            altitude: $altitude
        );

        $this->assertSame($latitude, $dto->latitude);
        $this->assertSame($longitude, $dto->longitude);
        $this->assertSame($altitude, $dto->altitude);
    }

    public function test_throws_exception_for_invalid_latitude(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between -90 and 90 degrees.');

        new LocationDto(latitude: 90.1, longitude: 0);
    }

    public function test_throws_exception_for_invalid_longitude(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between -180 and 180 degrees.');

        new LocationDto(latitude: 0, longitude: -180.1);
    }

    public function test_allows_boundary_values_for_coordinates(): void
    {
        $dto1 = new LocationDto(latitude: 90.0, longitude: 180.0);
        $this->assertSame(90.0, $dto1->latitude);
        $this->assertSame(180.0, $dto1->longitude);

        $dto2 = new LocationDto(latitude: -90.0, longitude: -180.0);
        $this->assertSame(-90.0, $dto2->latitude);
        $this->assertSame(-180.0, $dto2->longitude);
    }
}
