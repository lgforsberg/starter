<?php

declare(strict_types=1);

namespace App\Support;

final class Csrf
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function token(): string
    {
        if (!$this->session->has('_csrf_token')) {
            $this->session->set('_csrf_token', bin2hex(random_bytes(32)));
        }

        return $this->session->get('_csrf_token');
    }

    public function field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . $this->token() . '">';
    }

    public function validate(string $token): bool
    {
        $expected = $this->session->get('_csrf_token', '');
        return hash_equals($expected, $token);
    }

    public function regenerate(): void
    {
        $this->session->set('_csrf_token', bin2hex(random_bytes(32)));
    }
}
