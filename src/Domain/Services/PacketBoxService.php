<?php

declare(strict_types=1);

namespace Bramato\DimensionUtility\Domain\Services;

use Bramato\DimensionUtility\Domain\Dto\BoxDto;
use Bramato\DimensionUtility\Domain\Dto\ProductDto;
use Bramato\DimensionUtility\Domain\Dto\FullfilledBoxDto;
use Bramato\DimensionUtility\Dto\WeightDto;
use Bramato\DimensionUtility\Enum\DimensionEnum;
use Bramato\DimensionUtility\Enum\WeightEnum;
use Bramato\DimensionUtility\Services\DimensionConversionService;
use Bramato\DimensionUtility\Services\WeightConversionService;
use DVDoug\BoxPacker\Packer;
use DVDoug\BoxPacker\LimitedSupplyBox;
use DVDoug\BoxPacker\Item;
use DVDoug\BoxPacker\PackedBox;
use Bramato\DimensionUtility\Domain\ValueObject\PacketItem;
use RuntimeException;

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
     * @var array{'product': ProductDto, 'isPacked': bool, 'quantity': int}[] List of items to be packed.
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
     * @param int $quantity The quantity of this specific product (defaults to 1).
     * @return void
     */
    public function addItem(ProductDto $product, bool $isPacked = false, int $quantity = 1): void
    {
        $this->itemsToPack[] = [
            'product' => $product,
            'isPacked' => $isPacked,
            'quantity' => $quantity,
        ];
    }

    /**
     * Retrieves the list of items to be packed along with their packed status.
     *
     * @return array{'product': ProductDto, 'isPacked': bool, 'quantity': int}[]
     */
    public function getItemsToPack(): array
    {
        return $this->itemsToPack;
    }

    /**
     * Packs the added items into the available boxes using the BoxPacker library.
     *
     * Requires the dvdoug/boxpacker library to be installed.
     * Assumes ProductDto has getDimensionsInCM() and getWeightInG().
     * Assumes DimensionDto and WeightDto can be converted to CM and G respectively.
     *
     * @return FullfilledBoxDto[] An array of packed boxes with their contents.
     * @throws RuntimeException If packing fails or required services/methods are missing.
     */
    public function packItems(): array
    {
        $packer = new Packer();

        // --- Add Boxes to Packer ---
        foreach ($this->getAvailableBoxes() as $boxData) {
            /** @var BoxDto $boxDto */
            $boxDto = $boxData['box'];
            $quantity = $boxData['quantity'];

            // Convert dimensions/weights for BoxPacker (assuming CM and G are base units)
            // Using CM and Grams as consistent units.
            $outerLengthCm = DimensionConversionService::create($boxDto->length)->convert(DimensionEnum::CENTIMETER)->value;
            $outerWidthCm = DimensionConversionService::create($boxDto->width)->convert(DimensionEnum::CENTIMETER)->value;
            $outerHeightCm = DimensionConversionService::create($boxDto->height)->convert(DimensionEnum::CENTIMETER)->value;

            $innerLengthCm = DimensionConversionService::create($boxDto->innerLength)->convert(DimensionEnum::CENTIMETER)->value;
            $innerWidthCm = DimensionConversionService::create($boxDto->innerWidth)->convert(DimensionEnum::CENTIMETER)->value;
            $innerHeightCm = DimensionConversionService::create($boxDto->innerHeight)->convert(DimensionEnum::CENTIMETER)->value;

            $emptyWeightG = WeightConversionService::create($boxDto->weight)->convert(WeightEnum::GRAM)->value;
            $maxWeightG = WeightConversionService::create($boxDto->maxWeight)->convert(WeightEnum::GRAM)->value;
            $innerWeightCapacityG = $maxWeightG - $emptyWeightG;

            // Use Box reference ID to map back later
            $boxReference = spl_object_hash($boxDto);

            $packerBox = new LimitedSupplyBox(
                $boxReference,          // reference
                (int) $outerWidthCm,        // outerWidth (using CM as unit)
                (int) $outerLengthCm,       // outerLength
                (int) $outerHeightCm,       // outerDepth
                (int) $emptyWeightG,        // emptyWeight (in G)
                (int) $innerWidthCm,        // innerWidth
                (int) $innerLengthCm,       // innerLength
                (int) $innerHeightCm,       // innerDepth
                (int) $innerWeightCapacityG, // maxWeight (payload capacity in G)
                $quantity                 // quantity available
            );
            $packer->addBox($packerBox);
        }

        // --- Add Items to Packer ---
        $itemMapping = []; // To map packer item back to our ProductDto
        foreach ($this->getItemsToPack() as $itemData) {
            /** @var ProductDto $productDto */
            $productDto = $itemData['product'];
            $isPacked = $itemData['isPacked'];
            $quantity = $itemData['quantity'];

            if ($isPacked) {
                continue; // Skip items already marked as packed
            }

            $dimensions = $productDto->getDimensionsInCM(); // Assumes this returns a BoxDto
            $weightG = $productDto->getWeightInG(); // Assumes this returns float/int

            $itemLengthCm = DimensionConversionService::create($dimensions->length)->convert(DimensionEnum::CENTIMETER)->value;
            $itemWidthCm = DimensionConversionService::create($dimensions->width)->convert(DimensionEnum::CENTIMETER)->value;
            $itemHeightCm = DimensionConversionService::create($dimensions->height)->convert(DimensionEnum::CENTIMETER)->value;

            for ($i = 0; $i < $quantity; $i++) {
                // Use spl_object_hash + index for unique reference if multiple identical items
                $itemReference = spl_object_hash($productDto) . '-' . $i;

                // Using our custom PacketItem wrapper
                /** @var Item $packerItem */
                $packerItem = new PacketItem(
                    $itemReference,     // description / reference
                    (int) $itemWidthCm,     // width
                    (int) $itemLengthCm,    // length
                    (int) $itemHeightCm,    // depth
                    (int) $weightG,         // weight
                    true                // allowRotation
                );
                $packer->addItem($packerItem);
                $itemMapping[$itemReference] = $productDto; // Store mapping
            }
        }

        // --- Perform Packing --- 
        $packedBoxes = $packer->pack();

        // --- Convert Result to FullfilledBoxDto --- 
        $resultBoxes = [];
        $boxDtoMapping = []; // Cache mapping from reference ID back to BoxDto
        foreach ($this->getAvailableBoxes() as $boxData) {
            $boxDtoMapping[spl_object_hash($boxData['box'])] = $boxData['box'];
        }

        foreach ($packedBoxes as $packedBox) {
            /** @var PackedBox $packedBox */
            $packerBoxUsed = $packedBox->box;
            $boxReference = $packerBoxUsed->getReference();

            if (!isset($boxDtoMapping[$boxReference])) {
                // Should not happen if mapping is correct
                throw new RuntimeException("Could not find original BoxDto for packed box reference: {$boxReference}");
            }
            $originalBoxDto = $boxDtoMapping[$boxReference];

            $itemsInThisBox = [];
            $contentWeightG = 0;
            foreach ($packedBox->items as $packedItem) {
                /** @var Item $packedItem */
                $itemReference = $packedItem->getDescription();
                if (!isset($itemMapping[$itemReference])) {
                    // Should not happen
                    throw new RuntimeException("Could not find original ProductDto for packed item reference: {$itemReference}");
                }
                $originalProductDto = $itemMapping[$itemReference];
                $itemsInThisBox[] = $originalProductDto;
                $contentWeightG += $packedItem->getWeight();
            }

            // Calculate total weight (empty box + content)
            $emptyBoxWeightG = WeightConversionService::create($originalBoxDto->weight)->convert(WeightEnum::GRAM)->value;
            $totalWeightG = $emptyBoxWeightG + $contentWeightG;
            $totalWeightDto = new WeightDto((float)$totalWeightG, WeightEnum::GRAM);

            $resultBoxes[] = new FullfilledBoxDto(
                $originalBoxDto,
                $totalWeightDto,
                $itemsInThisBox // Add the packed items
            );
        }

        return $resultBoxes;
    }
}
