<?php

namespace App\Console\Commands;

use App\Models\AboutSection;
use App\Models\CompanySetting;
use App\Models\HeroSection;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\ImageUploader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Converts images that were uploaded before WebP support (JPG/PNG) to WebP
 * and points the database at the new files. Run once after deploying step 2.6.
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
                            {--dry-run : Only list what would be converted}
                            {--delete-originals : Remove the old JPG/PNG files after converting}';

    protected $description = 'Convert uploaded JPG/PNG images to WebP and update their database paths';

    /** model => [column => max width] */
    private const FIELDS = [
        CompanySetting::class => ['logo' => 600],
        HeroSection::class => ['background_image' => 1920, 'foreground_image' => 1400],
        AboutSection::class => ['image' => 1600],
        Service::class => ['image' => 1200],
        Portfolio::class => ['image' => 1600],
        TeamMember::class => ['photo' => 800],
        Testimonial::class => ['client_photo' => 400],
    ];

    public function handle(): int
    {
        if (!ImageUploader::supported()) {
            $this->error('PHP GD with WebP support is not available on this server (enable extension=gd).');

            return self::FAILURE;
        }

        $disk = Storage::disk('public');
        $converted = 0;
        $savedBytes = 0;

        foreach (self::FIELDS as $model => $columns) {
            foreach ($model::query()->get() as $record) {
                foreach ($columns as $column => $maxWidth) {
                    $path = $record->getRawOriginal($column);
                    if (!$path || str_ends_with(strtolower($path), '.webp') || !$disk->exists($path)) {
                        continue;
                    }

                    $before = $disk->size($path);
                    if ($this->option('dry-run')) {
                        $this->line("would convert {$path} (" . number_format($before / 1024, 1) . ' KB)');
                        continue;
                    }

                    $newPath = ImageUploader::convertStoredFile($path, $maxWidth);
                    if ($newPath === null) {
                        $this->warn("skipped {$path}");
                        continue;
                    }

                    $record->forceFill([$column => $newPath])->save();
                    $after = $disk->size($newPath);
                    $savedBytes += max(0, $before - $after);
                    $converted++;

                    if ($this->option('delete-originals')) {
                        $disk->delete($path);
                    }

                    $this->info(sprintf('%s → %s (%.1f KB → %.1f KB)', $path, $newPath, $before / 1024, $after / 1024));
                }
            }
        }

        $this->newLine();
        $this->info("Converted {$converted} image(s), saved " . number_format($savedBytes / 1024, 1) . ' KB.');

        return self::SUCCESS;
    }
}
