<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Dto;

use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Domain\Dto\ProductDto;
use Bramato\DimensionUtility\Domain\Dto\ProductPackageDto;
use Bramato\DimensionUtility\Dto\DimensionDto;
use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(ProductPackageDto::class)]
class ProductPackageDtoTest extends TestCase
{
    private ProductDto $product;
    private BoxDto $packageDimensions;
    private WeightDto $totalWeight;
    private WeightDto $emptyPackageWeight;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup common objects for tests
        $productDimensions = new BoxDto(
            new DimensionDto(10, DimensionEnum::CENTIMETER),
            new DimensionDto(5, DimensionEnum::CENTIMETER),
            new DimensionDto(2, DimensionEnum::CENTIMETER)
        );
        $productWeight = new WeightDto(0.5, WeightEnum::KILOGRAM); // 500g
        $this->product = new ProductDto('SKU001', 'Test Product', $productDimensions, $productWeight);

        $this->packageDimensions = new BoxDto(
            new DimensionDto(15, DimensionEnum::CENTIMETER),
            new DimensionDto(10, DimensionEnum::CENTIMETER),
            new DimensionDto(5, DimensionEnum::CENTIMETER)
        );

        $this->totalWeight = new WeightDto(0.7, WeightEnum::KILOGRAM); // 700g
        $this->emptyPackageWeight = new WeightDto(200, WeightEnum::GRAM); // 200g
    }

    public function test_can_be_instantiated_with_required_properties(): void
    {
        $dto = new ProductPackageDto(
            product: $this->product,
            packageDimensions: $this->packageDimensions,
            totalWeight: $this->totalWeight
        );

        $this->assertSame($this->product, $dto->product);
        $this->assertSame($this->packageDimensions, $dto->packageDimensions);
        $this->assertSame($this->totalWeight, $dto->totalWeight);
        $this->assertNull($dto->emptyPackageWeight); // Default is null
    }

    public function test_can_be_instantiated_with_optional_empty_weight(): void
    {
        $dto = new ProductPackageDto(
            product: $this->product,
            packageDimensions: $this->packageDimensions,
            totalWeight: $this->totalWeight,
            emptyPackageWeight: $this->emptyPackageWeight
        );

        $this->assertSame($this->emptyPackageWeight, $dto->emptyPackageWeight);
    }

    public function test_calculate_packaging_weight_works_correctly(): void
    {
        // Total weight 700g, Product weight 500g => Packaging should be 200g
        $dto = new ProductPackageDto(
            product: $this->product, // 0.5 KG
            packageDimensions: $this->packageDimensions,
            totalWeight: $this->totalWeight // 0.7 KG
        );

        $packagingWeight = $dto->calculatePackagingWeight();

        $this->assertInstanceOf(WeightDto::class, $packagingWeight);
        // Calculation is done in KG
        $this->assertEqualsWithDelta(0.2, $packagingWeight->value, 0.00001);
        $this->assertSame(WeightEnum::KILOGRAM, $packagingWeight->unit);

        // Test returning in GRAMS
        $packagingWeightGrams = $dto->calculatePackagingWeight(WeightEnum::GRAM);
        $this->assertInstanceOf(WeightDto::class, $packagingWeightGrams);
        $this->assertEqualsWithDelta(200.0, $packagingWeightGrams->value, 0.00001);
        $this->assertSame(WeightEnum::GRAM, $packagingWeightGrams->unit);
    }

    public function test_calculate_packaging_weight_returns_null_if_product_heavier_than_total(): void
    {
        $heavyProduct = new ProductDto(
            'SKU002',
            'Heavy Product',
            $this->product->dimensions,
            new WeightDto(1.0, WeightEnum::KILOGRAM) // 1kg, heavier than total
        );
        $dto = new ProductPackageDto(
            product: $heavyProduct,
            packageDimensions: $this->packageDimensions,
            totalWeight: $this->totalWeight // 0.7 KG
        );

        $this->assertNull($dto->calculatePackagingWeight());
    }


    public function test_get_packaging_weight_returns_provided_weight_if_available(): void
    {
        $dto = new ProductPackageDto(
            product: $this->product,
            packageDimensions: $this->packageDimensions,
            totalWeight: $this->totalWeight,
            emptyPackageWeight: $this->emptyPackageWeight // Provided as 200 GRAM
        );

        // Get in default unit (GRAM)
        $packagingWeight = $dto->getPackagingWeight();
        $this->assertSame($this->emptyPackageWeight, $packagingWeight); // Returns the original DTO

        // Get in different unit (KG)
        $packagingWeightKg = $dto->getPackagingWeight(WeightEnum::KILOGRAM);
        $this->assertInstanceOf(WeightDto::class, $packagingWeightKg);
        $this->assertEqualsWithDelta(0.2, $packagingWeightKg->value, 0.00001);
        $this->assertSame(WeightEnum::KILOGRAM, $packagingWeightKg->unit);
    }

    public function test_get_packaging_weight_calculates_if_not_provided(): void
    {
        $dto = new ProductPackageDto(
            product: $this->product, // 0.5 KG
            packageDimensions: $this->packageDimensions,
            totalWeight: $this->totalWeight // 0.7 KG
            // emptyPackageWeight is null
        );

        // Calculate and return in default unit (KG, from totalWeight)
        $packagingWeight = $dto->getPackagingWeight();
        $this->assertInstanceOf(WeightDto::class, $packagingWeight);
        $this->assertEqualsWithDelta(0.2, $packagingWeight->value, 0.00001);
        $this->assertSame(WeightEnum::KILOGRAM, $packagingWeight->unit); // Uses totalWeight's unit

        // Calculate and return in GRAM
        $packagingWeightGrams = $dto->getPackagingWeight(WeightEnum::GRAM);
        $this->assertInstanceOf(WeightDto::class, $packagingWeightGrams);
        $this->assertEqualsWithDelta(200.0, $packagingWeightGrams->value, 0.00001);
        $this->assertSame(WeightEnum::GRAM, $packagingWeightGrams->unit);
    }
}
