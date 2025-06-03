<?php
/* Ricorda:
I messaggi flash vengono rimossi dalla sessione appena letti, quindi devono essere stampati prima di qualunque altro FlashMessages::get() nel ciclo di vita della pagina.
*/

class FlashMessages {

    const SESSION_KEY = '_flash_messages';

    public static function add(string $type, string $message): void {
        $_SESSION[self::SESSION_KEY][$type][] = $message;
    }

    public static function get(?string $type = null): array {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return [];
        }

        if ($type) {
            $messages = $_SESSION[self::SESSION_KEY][$type] ?? [];
            unset($_SESSION[self::SESSION_KEY][$type]);
            return $messages;
        }

        $allMessages = $_SESSION[self::SESSION_KEY];
        unset($_SESSION[self::SESSION_KEY]);
        return $allMessages;
    }

    public static function has(?string $type = null): bool {
        if ($type) {
            return !empty($_SESSION[self::SESSION_KEY][$type]);
        }
        return !empty($_SESSION[self::SESSION_KEY]);
    }
}
