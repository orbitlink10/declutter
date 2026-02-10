<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ItemImageService
{
    public function storeImages(Item $item, array $uploadedImages): void
    {
        $startingOrder = (int) $item->images()->max('sort_order');

        foreach ($uploadedImages as $index => $uploadedImage) {
            if (! $uploadedImage instanceof UploadedFile) {
                continue;
            }

            $paths = $this->createMainAndThumb($uploadedImage, $item->id, $startingOrder + $index + 1);

            $item->images()->create([
                'path' => $paths['path'],
                'thumb_path' => $paths['thumb_path'],
                'sort_order' => $startingOrder + $index + 1,
            ]);
        }
    }

    public function deleteImage(ItemImage $itemImage): void
    {
        Storage::disk('public')->delete([$itemImage->path, $itemImage->thumb_path]);
        $itemImage->delete();
    }

    public function deleteAllForItem(Item $item): void
    {
        $item->loadMissing('images');

        foreach ($item->images as $image) {
            Storage::disk('public')->delete([$image->path, $image->thumb_path]);
        }
    }

    /**
     * @return array{path: string, thumb_path: string}
     */
    private function createMainAndThumb(UploadedFile $uploadedFile, int $itemId, int $index): array
    {
        if (! $this->canTransformImages()) {
            return $this->storeWithoutTransform($uploadedFile, $itemId, $index);
        }

        $image = $this->createImageResource($uploadedFile->getRealPath(), $uploadedFile->getMimeType() ?: '');

        if (! $image) {
            return $this->storeWithoutTransform($uploadedFile, $itemId, $index);
        }

        $main = $this->resize($image, 1600, 1600);
        $thumb = $this->resize($image, 420, 420);

        \imagedestroy($image);

        $basename = Str::uuid()->toString().'-'.$index;
        $directory = "item-images/{$itemId}";
        $mainPath = "{$directory}/{$basename}.jpg";
        $thumbPath = "{$directory}/{$basename}-thumb.jpg";

        Storage::disk('public')->put($mainPath, $this->renderJpeg($main, 85));
        Storage::disk('public')->put($thumbPath, $this->renderJpeg($thumb, 80));

        \imagedestroy($main);
        \imagedestroy($thumb);

        return [
            'path' => $mainPath,
            'thumb_path' => $thumbPath,
        ];
    }

    private function createImageResource(string $path, string $mime): mixed
    {
        $resource = match ($mime) {
            'image/jpeg', 'image/jpg' => \function_exists('imagecreatefromjpeg') ? \imagecreatefromjpeg($path) : null,
            'image/png' => \function_exists('imagecreatefrompng') ? \imagecreatefrompng($path) : null,
            'image/webp' => \function_exists('imagecreatefromwebp') ? \imagecreatefromwebp($path) : null,
            default => null,
        };

        if (! $resource) {
            $content = file_get_contents($path);

            if ($content !== false) {
                $resource = \function_exists('imagecreatefromstring') ? @\imagecreatefromstring($content) : null;
            }
        }

        return $resource;
    }

    private function resize(mixed $source, int $maxWidth, int $maxHeight): mixed
    {
        $sourceWidth = \imagesx($source);
        $sourceHeight = \imagesy($source);

        $scale = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight, 1);
        $targetWidth = (int) round($sourceWidth * $scale);
        $targetHeight = (int) round($sourceHeight * $scale);

        $target = \imagecreatetruecolor($targetWidth, $targetHeight);
        \imagefill($target, 0, 0, \imagecolorallocate($target, 255, 255, 255));
        \imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

        return $target;
    }

    private function renderJpeg(mixed $resource, int $quality): string
    {
        \ob_start();
        \imagejpeg($resource, null, $quality);

        return (string) \ob_get_clean();
    }

    private function canTransformImages(): bool
    {
        return \function_exists('imagecreatefromjpeg')
            && \function_exists('imagecreatefrompng')
            && \function_exists('imagecreatetruecolor')
            && \function_exists('imagecopyresampled')
            && \function_exists('imagejpeg');
    }

    /**
     * Store files without processing when GD is unavailable.
     *
     * @return array{path: string, thumb_path: string}
     */
    private function storeWithoutTransform(UploadedFile $uploadedFile, int $itemId, int $index): array
    {
        $basename = Str::uuid()->toString().'-'.$index;
        $extension = strtolower($uploadedFile->getClientOriginalExtension() ?: 'jpg');
        $directory = "item-images/{$itemId}";
        $mainPath = "{$directory}/{$basename}.{$extension}";
        $thumbPath = "{$directory}/{$basename}-thumb.{$extension}";

        $stream = \fopen($uploadedFile->getRealPath(), 'rb');
        if ($stream === false) {
            throw new RuntimeException('Failed to read uploaded image.');
        }

        Storage::disk('public')->writeStream($mainPath, $stream);
        if (\is_resource($stream)) {
            \fclose($stream);
        }

        $thumbStream = \fopen($uploadedFile->getRealPath(), 'rb');
        if ($thumbStream === false) {
            throw new RuntimeException('Failed to read uploaded image.');
        }

        Storage::disk('public')->writeStream($thumbPath, $thumbStream);
        if (\is_resource($thumbStream)) {
            \fclose($thumbStream);
        }

        return [
            'path' => $mainPath,
            'thumb_path' => $thumbPath,
        ];
    }
}
