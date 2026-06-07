<?php

declare(strict_types=1);

namespace App\Support;

final class Honeypot
{
    private const FIELD_NAME = 'website';
    private const TIME_FIELD = '_form_rendered';
    private const MIN_SECONDS = 2;

    public function field(): string
    {
        $time = time();
        return '<div style="position:absolute;left:-9999px" aria-hidden="true">'
            . '<input type="text" name="' . self::FIELD_NAME . '" value="" autocomplete="off" tabindex="-1">'
            . '</div>'
            . '<input type="hidden" name="' . self::TIME_FIELD . '" value="' . $time . '">';
    }

    public function isSpam(array $body): bool
    {
        if (!empty($body[self::FIELD_NAME] ?? '')) {
            return true;
        }

        $rendered = (int) ($body[self::TIME_FIELD] ?? 0);
        if ($rendered > 0 && (time() - $rendered) < self::MIN_SECONDS) {
            return true;
        }

        return false;
    }
}
