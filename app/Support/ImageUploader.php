<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores images uploaded from the dashboard as WebP (much smaller than JPG/PNG),
 * scaled down to a sensible maximum width. SVG/GIF/ICO files and servers without
 * GD WebP support fall back to storing the original file unchanged.
 */
class ImageUploader
{
    private const CONVERTIBLE = ['image/jpeg', 'image/png', 'image/webp'];

    public static function store(UploadedFile $file, string $directory, int $maxWidth = 1920, int $quality = 82): string
    {
        $webp = self::toWebp($file->getRealPath(), (string) $file->getMimeType(), $maxWidth, $quality);

        if ($webp === null) {
            return $file->store($directory, 'public');
        }

        $path = trim($directory, '/') . '/' . Str::random(40) . '.webp';
        Storage::disk('public')->put($path, $webp);

        return $path;
    }

    /**
     * Convert an existing file on the public disk (used by `images:optimize`).
     * Returns the new path, or null when the file was left as it is.
     */
    public static function convertStoredFile(string $path, int $maxWidth = 1920, int $quality = 82): ?string
    {
        $disk = Storage::disk('public');
        if (!$disk->exists($path) || str_ends_with(strtolower($path), '.webp')) {
            return null;
        }

        $absolute = $disk->path($path);
        $webp = self::toWebp($absolute, (string) mime_content_type($absolute), $maxWidth, $quality);
        if ($webp === null) {
            return null;
        }

        $newPath = preg_replace('/\.[^.\/]+$/', '', $path) . '.webp';
        $disk->put($newPath, $webp);

        return $newPath;
    }

    public static function supported(): bool
    {
        return function_exists('imagewebp') && function_exists('imagecreatefromstring');
    }

    private static function toWebp(string $sourcePath, string $mime, int $maxWidth, int $quality): ?string
    {
        if (!self::supported() || !in_array($mime, self::CONVERTIBLE, true)) {
            return null;
        }

        $image = @imagecreatefromstring((string) file_get_contents($sourcePath));
        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * $maxWidth / $width);
            $resized = imagecreatetruecolor($maxWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        } elseif (!imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();
        $ok = imagewebp($image, null, $quality);
        $data = ob_get_clean();
        imagedestroy($image);

        return $ok && $data !== '' ? $data : null;
    }
}
