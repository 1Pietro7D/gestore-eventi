<?php
class Csrf {
    const SESSION_KEY = '_csrf_token';

    public static function init(): void {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
    }

    public static function token(): string {
        return $_SESSION[self::SESSION_KEY] ?? '';
    }

    public static function check(?string $token): bool {
        return isset($_SESSION[self::SESSION_KEY]) && hash_equals($_SESSION[self::SESSION_KEY], $token ?? '');
    }
}

function csrf_token(): string {
    return Csrf::token();
}

function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}