<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\WeightDto;

/**
 * Trait for objects having physical dimensions and weight.
 */
trait PhysicalObjectTrait
{
    /**
     * Physical dimensions of the object.
     */
    public readonly BoxDto $dimensions;

    /**
     * Weight of the object.
     */
    public readonly WeightDto $weight;

    // Note: Constructor needs to be implemented in the class using the trait
    // Example constructor for a class using this trait:
    /*
    public function __construct(BoxDto $dimensions, WeightDto $weight, ...) {
        $this->dimensions = $dimensions;
        $this->weight = $weight;
        // ... other properties
    }
    */
}
