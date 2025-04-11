<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Dto;

use Bramato\DimensionUtility\Domain\Dto\LocationDto;
use Bramato\DimensionUtility\Domain\Dto\WeatherReadingDto;
use Bramato\DimensionUtility\Dto\PressureDto;
use Bramato\DimensionUtility\Dto\SpeedDto;
use Bramato\DimensionUtility\Dto\TemperatureDto;
use Bramato\DimensionUtility\Enum\PressureEnum;
use Bramato\DimensionUtility\Enum\SpeedEnum;
use Bramato\DimensionUtility\Enum\TemperatureEnum;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(WeatherReadingDto::class)]
class WeatherReadingDtoTest extends TestCase
{
    private DateTimeImmutable $timestamp;
    private LocationDto $location;
    private TemperatureDto $temperature;
    private PressureDto $pressure;

    protected function setUp(): void
    {
        parent::setUp();
        $this->timestamp = new DateTimeImmutable('2025-03-20 14:30:00');
        $this->location = new LocationDto(latitude: 41.9028, longitude: 12.4964); // Rome
        $this->temperature = new TemperatureDto(15, TemperatureEnum::CELSIUS);
        $this->pressure = new PressureDto(1013, PressureEnum::MILLIBAR);
    }

    public function test_can_be_instantiated_with_required_properties(): void
    {
        $humidity = 65.5; // Assuming percentage

        $dto = new WeatherReadingDto(
            timestamp: $this->timestamp,
            location: $this->location,
            temperature: $this->temperature,
            pressure: $this->pressure,
            humidity: $humidity
        );

        $this->assertSame($this->timestamp, $dto->timestamp);
        $this->assertSame($this->location, $dto->location);
        $this->assertSame($this->temperature, $dto->temperature);
        $this->assertSame($this->pressure, $dto->pressure);
        $this->assertSame($humidity, $dto->humidity);
        $this->assertNull($dto->windSpeed);
        $this->assertNull($dto->windDirection);
    }

    public function test_can_be_instantiated_with_all_properties(): void
    {
        $humidity = 70.0;
        $windSpeed = new SpeedDto(15, SpeedEnum::KILOMETER_PER_HOUR);
        $windDirection = 270.0; // West

        $dto = new WeatherReadingDto(
            timestamp: $this->timestamp,
            location: $this->location,
            temperature: $this->temperature,
            pressure: $this->pressure,
            humidity: $humidity,
            windSpeed: $windSpeed,
            windDirection: $windDirection
        );

        $this->assertSame($windSpeed, $dto->windSpeed);
        $this->assertSame($windDirection, $dto->windDirection);
    }

    public function test_throws_exception_for_negative_humidity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Humidity cannot be negative.');

        new WeatherReadingDto(
            timestamp: $this->timestamp,
            location: $this->location,
            temperature: $this->temperature,
            pressure: $this->pressure,
            humidity: -10.0 // Invalid humidity
        );
    }

    public function test_throws_exception_for_invalid_wind_direction(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Wind direction must be between 0 and 360 degrees.');

        new WeatherReadingDto(
            timestamp: $this->timestamp,
            location: $this->location,
            temperature: $this->temperature,
            pressure: $this->pressure,
            humidity: 50.0,
            windSpeed: new SpeedDto(5, SpeedEnum::METER_PER_SECOND),
            windDirection: 361.0 // Invalid direction
        );
    }

    public function test_allows_boundary_values_for_wind_direction(): void
    {
        $dto1 = new WeatherReadingDto(
            timestamp: $this->timestamp,
            location: $this->location,
            temperature: $this->temperature,
            pressure: $this->pressure,
            humidity: 50.0,
            windDirection: 0.0
        );
        $this->assertSame(0.0, $dto1->windDirection);

        $dto2 = new WeatherReadingDto(
            timestamp: $this->timestamp,
            location: $this->location,
            temperature: $this->temperature,
            pressure: $this->pressure,
            humidity: 50.0,
            windDirection: 360.0
        );
        $this->assertSame(360.0, $dto2->windDirection);
    }
}
