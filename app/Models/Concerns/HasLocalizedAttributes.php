<?php

namespace App\Models\Concerns;

trait HasLocalizedAttributes
{
    /**
     * Resolve locale-specific attribute with fallback.
     *
     * Fallback order:
     * 1) current locale column (e.g. title_ar)
     * 2) english column (e.g. title_en)
     * 3) legacy single-language column value
     */
    protected function resolveLocalizedAttribute(string $baseName, mixed $legacyValue = null): mixed
    {
        $locale = app()->getLocale();

        $localizedKey = "{$baseName}_{$locale}";
        $englishKey = "{$baseName}_en";

        $localizedValue = $this->attributes[$localizedKey] ?? null;
        if ($this->hasMeaningfulValue($localizedValue)) {
            return $localizedValue;
        }

        $englishValue = $this->attributes[$englishKey] ?? null;
        if ($this->hasMeaningfulValue($englishValue)) {
            return $englishValue;
        }

        return $legacyValue;
    }

    /**
     * Resolve locale-specific JSON array attribute with fallback.
     */
    protected function resolveLocalizedJsonArray(string $baseName, mixed $legacyValue = null): array
    {
        $value = $this->resolveLocalizedAttribute($baseName, $legacyValue);

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    protected function hasMeaningfulValue(mixed $value): bool
    {
        if (is_array($value)) {
            return !empty($value);
        }

        return $value !== null && $value !== '';
    }
}
