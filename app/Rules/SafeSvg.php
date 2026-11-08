<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * SVG files are XML that can carry JavaScript. Uploaded images are served straight from
 * /storage, so an SVG with a script would run on the site's own domain. Reject any SVG
 * that contains scripts, event handlers, javascript: links or external/foreign content.
 */
class SafeSvg implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            return;
        }

        $isSvg = str_contains((string) $value->getMimeType(), 'svg')
            || strtolower($value->getClientOriginalExtension()) === 'svg';
        if (!$isSvg) {
            return;
        }

        $content = strtolower((string) file_get_contents($value->getRealPath()));
        $dangerous = ['<script', 'javascript:', '<foreignobject', '<iframe', '<embed', '<object', 'xlink:href="http', 'href="http', '<!entity'];

        foreach ($dangerous as $needle) {
            if (str_contains($content, $needle)) {
                $fail(__('admin.svg_unsafe'));

                return;
            }
        }

        if (preg_match('/\son[a-z]+\s*=/', $content)) {
            $fail(__('admin.svg_unsafe'));
        }
    }
}
