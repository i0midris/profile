<?php

namespace Tests\Unit;

use App\Support\PhoneNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public static function numbers(): array
    {
        return [
            'double zero with dash' => ['00967-777335118'],
            'plus with spaces' => ['+967 777 335 118'],
            'local with trunk zero' => ['0777335118'],
            'local without code' => ['777335118'],
            'country code only' => ['967777335118'],
        ];
    }

    #[DataProvider('numbers')]
    public function test_normalizes_yemeni_numbers(string $input): void
    {
        $this->assertSame('967777335118', PhoneNumber::digits($input));
        $this->assertSame('+967777335118', PhoneNumber::e164($input));
        $this->assertSame('https://wa.me/967777335118', PhoneNumber::whatsappUrl($input));
        $this->assertSame('tel:+967777335118', PhoneNumber::telUrl($input));
        $this->assertSame('+967 777 335 118', PhoneNumber::display($input));
    }

    public function test_keeps_other_country_codes(): void
    {
        $this->assertSame('https://wa.me/966501234567', PhoneNumber::whatsappUrl('+966 50 123 4567'));
    }

    public function test_empty_values_return_null(): void
    {
        $this->assertNull(PhoneNumber::whatsappUrl(null));
        $this->assertNull(PhoneNumber::telUrl(''));
        $this->assertNull(PhoneNumber::display(' - '));
    }
}
