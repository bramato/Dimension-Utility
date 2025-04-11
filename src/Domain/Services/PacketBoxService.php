<?php

declare(strict_types=1);

namespace Bramato\DimensionUtility\Domain\Services;

use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Domain\Dto\ProductDto;

/**
 * Service to manage box packing using an external library (like BoxPacker).
 * Handles the available box types and the items to be packed.
 */
class PacketBoxService
{
    /**
     * @var array{'box': BoxDto, 'quantity': int}[] List of available box types and their quantities.
     */
    private array $availableBoxes = [];

    /**
     * @var array{'product': ProductDto, 'isPacked': bool}[] List of items to be packed.
     */
    private array $itemsToPack = [];

    /**
     * Adds a definition for an available box type.
     *
     * @param BoxDto $box The box definition (including dimensions, weight, etc.).
     * @param int $quantity The number of this type of box available (defaults to 100).
     * @return void
     */
    public function addBox(BoxDto $box, int $quantity = 100): void
    {
        // Optionally, add a check to prevent adding duplicate box definitions
        // based on dimensions or a unique identifier if BoxDto had one.
        $this->availableBoxes[] = [
            'box' => $box,
            'quantity' => $quantity,
        ];
    }

    /**
     * Retrieves the list of currently available box types.
     *
     * @return array{'box': BoxDto, 'quantity': int}[]
     */
    public function getAvailableBoxes(): array
    {
        return $this->availableBoxes;
    }

    /**
     * Adds an item to the list of products to be potentially packed.
     *
     * @param ProductDto $product The product DTO.
     * @param bool $isPacked Flag indicating if the product is already considered packed (e.g., pre-packed item).
     * @return void
     */
    public function addItem(ProductDto $product, bool $isPacked = false): void
    {
        $this->itemsToPack[] = [
            'product' => $product,
            'isPacked' => $isPacked,
        ];
    }

    /**
     * Retrieves the list of items to be packed along with their packed status.
     *
     * @return array{'product': ProductDto, 'isPacked': bool}[]
     */
    public function getItemsToPack(): array
    {
        return $this->itemsToPack;
    }

    // Future methods will handle adding items and performing the packing calculation.
}
