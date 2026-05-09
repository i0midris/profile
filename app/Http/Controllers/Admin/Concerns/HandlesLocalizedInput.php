<?php

namespace App\Http\Controllers\Admin\Concerns;

trait HandlesLocalizedInput
{
    /**
     * Normalize legacy single-language and bilingual fields into a consistent payload.
     *
     * For each field:
     * - fill *_en / *_ar from legacy field when localized value is empty
     * - fill legacy field from *_en fallback *_ar when legacy field is empty
     */
    protected function hydrateLocalizedFields(array $validated, array $fields): array
    {
        foreach ($fields as $field) {
            $legacyKey = $field;
            $enKey = "{$field}_en";
            $arKey = "{$field}_ar";

            $legacyValue = $validated[$legacyKey] ?? null;
            $enValue = $validated[$enKey] ?? null;
            $arValue = $validated[$arKey] ?? null;

            if (!$this->hasValue($enValue) && $this->hasValue($legacyValue)) {
                $validated[$enKey] = $legacyValue;
            }

            if (!$this->hasValue($arValue) && $this->hasValue($legacyValue)) {
                $validated[$arKey] = $legacyValue;
            }

            if (!$this->hasValue($legacyValue)) {
                $validated[$legacyKey] = $validated[$enKey] ?? $validated[$arKey] ?? null;
            }
        }

        return $validated;
    }

    private function hasValue(mixed $value): bool
    {
        if (is_array($value)) {
            return count(array_filter($value, fn($item) => $item !== null && $item !== '')) > 0;
        }

        return $value !== null && $value !== '';
    }
}
