<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * PHP silently keeps the last value when an array literal repeats a key. In translation
 * files that turns a label into something else (this broke the admin About screen once),
 * so duplicate top-level keys are not allowed, and both languages must define the same keys.
 */
class TranslationFilesTest extends TestCase
{
    private function topLevelKeys(string $file): array
    {
        preg_match_all("/^  '([^']+)'\s*=>/m", file_get_contents($file), $m);

        return $m[1];
    }

    public function test_translation_files_have_no_duplicate_keys(): void
    {
        foreach (glob(__DIR__ . '/../../resources/lang/*/*.php') as $file) {
            $keys = $this->topLevelKeys($file);
            $duplicates = array_keys(array_filter(array_count_values($keys), fn ($n) => $n > 1));
            $this->assertSame([], $duplicates, 'Duplicate keys in ' . basename(dirname($file)) . '/' . basename($file));
        }
    }

    public function test_arabic_and_english_define_the_same_keys(): void
    {
        foreach (['frontend', 'admin'] as $group) {
            $ar = array_keys(require __DIR__ . "/../../resources/lang/ar/{$group}.php");
            $en = array_keys(require __DIR__ . "/../../resources/lang/en/{$group}.php");
            $this->assertSame([], array_values(array_diff($ar, $en)), "Keys only in ar/{$group}.php");
            $this->assertSame([], array_values(array_diff($en, $ar)), "Keys only in en/{$group}.php");
        }
    }
}
