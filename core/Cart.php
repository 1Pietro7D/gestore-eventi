<?php

class Cart {

    const SESSION_KEY = '_cart';

    public static function add(Prodotto $prodotto, int $qty): void {
        $_SESSION[self::SESSION_KEY][$prodotto->id] = [
            'prodotto' => $prodotto,
            'quantita' => $qty,
        ];
    }

    public static function remove(int $id): void {
        unset($_SESSION[self::SESSION_KEY][$id]);
    }

    public static function clear(): void {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public static function items(): array {
        return $_SESSION[self::SESSION_KEY] ?? [];
    }

    public static function total(): float {
        $total = 0;
        foreach (self::items() as $item) {
            $total += $item['prodotto']->prezzo * $item['quantita'];
        }
        return $total;
    }
}
