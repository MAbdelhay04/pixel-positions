<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class ImageOptimizationService
{
    public function __construct(
        private ImageManager $manager
    ) {}

    public function store(
        UploadedFile $file,
        string $folder = 'logos',
        int $width = 300,
        ?int $height = null,
        int $quality = 80,
        string $format = 'webp',
        bool $crop = false
    ): string {

        $format = strtolower($format);

        if ($this->isSvg($file)) {
            return $file->store($folder, 'public');
        }

        $filename = sprintf(
            '%s/%s.%s',
            trim($folder, '/'),
            (string) str()->uuid(),
            $format
        );

        $image = $this->manager->decode($file);

        if ($crop && $height) {
            $image->cover($width, $height);
        } else {
            $image->scale(width: $width, height: $height);
        }

        $formatEnum = match ($format) {
            'webp' => Format::WEBP,
            'png'  => Format::PNG,
            'gif'  => Format::GIF,
            default => Format::JPEG,
        };

        $encoded = $image->encodeUsingFormat(
            $formatEnum,
            quality: $quality
        );

        Storage::disk('public')->put(
            $filename,
            (string) $encoded
        );

        return $filename;
    }

    private function isSvg(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), [
            'image/svg+xml'
        ], true);
    }
}
