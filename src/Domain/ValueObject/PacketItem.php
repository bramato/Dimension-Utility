<?php

declare(strict_types=1);

namespace Bramato\DimensionUtility\Domain\ValueObject;

use Bramato\DimensionUtility\Domain\Dto\ProductDto;
use DVDoug\BoxPacker\Item;
use DVDoug\BoxPacker\Rotation;

/**
 * A simple wrapper for ProductDto to be used with BoxPacker,
 * implementing the Item interface.
 */
class PacketItem implements Item
{
    public function __construct(
        private readonly string $reference, // Unique reference (e.g., spl_object_hash + index)
        private readonly int $width,        // Width in consistent units (e.g., CM)
        private readonly int $length,       // Length in consistent units (e.g., CM)
        private readonly int $depth,        // Depth/Height in consistent units (e.g., CM)
        private readonly int $weight,       // Weight in consistent units (e.g., Grams)
        private readonly bool $allowRotation // Whether the item can be rotated
    ) {}

    public function getDescription(): string
    {
        return $this->reference;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getAllowedRotation(): Rotation
    {
        // Corresponds to Rotation::BestFit in BoxPacker v3+
        // Or return specific allowed rotation types if needed.
        // For simplicity, mirroring TestItem's behavior (effectively allow all rotations).
        return $this->allowRotation ? Rotation::BestFit : Rotation::KeepFlat;
    }

    public function getKeepFlat(): bool
    {
        // Relevant for BoxPacker v2, less so for v3 with AllowedRotation.
        // Return false if rotation is allowed.
        return !$this->allowRotation;
    }
}
