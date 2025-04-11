<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Dto;

use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Domain\Dto\FullfilledBoxDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(FullfilledBoxDto::class)]
class FullfilledBoxDtoTest extends TestCase
{
    public function test_can_be_instantiated_and_properties_accessed(): void
    {
        $dimensions = new BoxDto(
            new DimensionDto(30, DimensionEnum::CENTIMETER),
            new DimensionDto(20, DimensionEnum::CENTIMETER),
            new DimensionDto(15, DimensionEnum::CENTIMETER)
        );
        $totalWeight = new WeightDto(2.5, WeightEnum::KILOGRAM);

        $dto = new FullfilledBoxDto(
            dimensions: $dimensions,
            totalWeight: $totalWeight
        );

        $this->assertSame($dimensions, $dto->dimensions);
        $this->assertSame($totalWeight, $dto->totalWeight);
    }

    public function test_create_metric_factory_creates_dto_with_correct_units(): void
    {
        $dto = FullfilledBoxDto::createMetric(
            lengthCm: 40,
            widthCm: 30,
            heightCm: 20,
            weightKg: 5.5
        );

        $this->assertInstanceOf(FullfilledBoxDto::class, $dto);
        $this->assertSame(40.0, $dto->dimensions->length->value);
        $this->assertSame(DimensionEnum::CENTIMETER, $dto->dimensions->length->unit);
        $this->assertSame(30.0, $dto->dimensions->width->value);
        $this->assertSame(DimensionEnum::CENTIMETER, $dto->dimensions->width->unit);
        $this->assertSame(20.0, $dto->dimensions->height->value);
        $this->assertSame(DimensionEnum::CENTIMETER, $dto->dimensions->height->unit);
        $this->assertSame(5.5, $dto->totalWeight->value);
        $this->assertSame(WeightEnum::KILOGRAM, $dto->totalWeight->unit);
    }

    public function test_create_imperial_factory_creates_dto_with_correct_units(): void
    {
        $dto = FullfilledBoxDto::createImperial(
            lengthInch: 16,
            widthInch: 12,
            heightInch: 8,
            weightPound: 12.1
        );

        $this->assertInstanceOf(FullfilledBoxDto::class, $dto);
        $this->assertSame(16.0, $dto->dimensions->length->value);
        $this->assertSame(DimensionEnum::INCH, $dto->dimensions->length->unit);
        $this->assertSame(12.0, $dto->dimensions->width->value);
        $this->assertSame(DimensionEnum::INCH, $dto->dimensions->width->unit);
        $this->assertSame(8.0, $dto->dimensions->height->value);
        $this->assertSame(DimensionEnum::INCH, $dto->dimensions->height->unit);
        $this->assertSame(12.1, $dto->totalWeight->value);
        $this->assertSame(WeightEnum::POUND, $dto->totalWeight->unit);
    }
}
