<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Dto;

use Bramato\DimensionUtility\Dto\DataStorageDto;
use Bramato\DimensionUtility\Enum\DataStorageEnum;
use Bramato\DimensionUtility\Domain\Dto\FileInfoDto;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(FileInfoDto::class)]
class FileInfoDtoTest extends TestCase
{
    public function test_can_be_instantiated_with_required_properties(): void
    {
        $path = '/path/to/document.pdf';
        $size = new DataStorageDto(1024 * 5, DataStorageEnum::BYTE); // 5 KiB

        $dto = new FileInfoDto(
            path: $path,
            size: $size
            // mimeType is optional
        );

        $this->assertSame($path, $dto->path);
        $this->assertSame($size, $dto->size);
        $this->assertNull($dto->mimeType); // Default is null
    }

    public function test_can_be_instantiated_with_all_properties(): void
    {
        $path = 'C:\\Users\\Test\\image.jpg';
        $size = new DataStorageDto(2, DataStorageEnum::MEBIBYTE); // 2 MiB
        $mimeType = 'image/jpeg';
        // Removed lastModified and checksum

        $dto = new FileInfoDto(
            path: $path,
            size: $size,
            mimeType: $mimeType
        );

        $this->assertSame($path, $dto->path);
        $this->assertSame($size, $dto->size);
        $this->assertSame($mimeType, $dto->mimeType);
    }

    public function test_get_filename_returns_correct_basename(): void
    {
        $path = '/var/log/app.log';
        $size = new DataStorageDto(100, DataStorageEnum::BYTE);
        $dto = new FileInfoDto(path: $path, size: $size);

        $this->assertSame('app.log', $dto->getFilename());

        $pathWindows = 'D:\\Data\\archive.zip';
        $dtoWindows = new FileInfoDto(path: $pathWindows, size: $size);
        $this->assertSame('archive.zip', $dtoWindows->getFilename());
    }

    public function test_get_extension_returns_correct_extension(): void
    {
        $path = '/path/to/archive.tar.gz';
        $size = new DataStorageDto(1, DataStorageEnum::GIBIBYTE);
        $dto = new FileInfoDto(path: $path, size: $size);

        // pathinfo gets the last extension
        $this->assertSame('gz', $dto->getExtension());

        $pathNoExt = '/path/to/file_without_extension';
        $dtoNoExt = new FileInfoDto(path: $pathNoExt, size: $size);
        $this->assertNull($dtoNoExt->getExtension());
    }
}
