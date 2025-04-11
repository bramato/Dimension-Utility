<?php

namespace Bramato\DimensionUtility\Domain\Dto;

use Bramato\DimensionUtility\Dto\DataStorageDto;

/**
 * Represents information about a digital file.
 */
class FileInfoDto
{
    /**
     * Creates a new FileInfoDto instance.
     *
     * @param string $path The full path to the file.
     * @param DataStorageDto $size The size of the file.
     * @param string|null $mimeType Optional: The MIME type of the file.
     */
    public function __construct(
        public readonly string $path,
        public readonly DataStorageDto $size,
        public readonly ?string $mimeType = null
    ) {}

    /**
     * Gets the file extension from the path.
     *
     * @return string|null The file extension (without the dot) or null if none found.
     */
    public function getExtension(): ?string
    {
        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        return $extension ?: null;
    }

    /**
     * Gets the filename from the path.
     *
     * @return string The filename including extension.
     */
    public function getFilename(): string
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }
}
