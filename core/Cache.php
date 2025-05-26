<?php

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
// Autoload delle dipendenze installate con Composer (es. Symfony Cache)
require_once __DIR__ . '/../vendor/autoload.php';

class Cache {
    protected static FilesystemAdapter $cache;

    protected static function getAdapter(): FilesystemAdapter
    {
        if (!isset(self::$cache)) {
            self::$cache = new FilesystemAdapter();
        }
        return self::$cache;
    }

    public static function view(string $key, int $ttl, callable $callback): string {
        $fromCache = false;

        $cache = self::getAdapter();

        $item = $cache->getItem($key);

        if ($item->isHit()) {
            $fromCache = true;
            $content = $item->get();
        } else {
            $result = $callback();

            // Non salviamo se è null, false o vuoto
            if (!empty($result)) {
                $item->set($result);
                $item->expiresAfter($ttl);
                $cache->save($item);
            }

            $content = $result ?: ''; // fallback
        }

        $debugNote = $fromCache
            ? "\n<!-- Caricato dalla cache $key -->"
            : "\n<!-- Caricato dal database $key -->";

        return $content . $debugNote;
    }
    
    public static function remember(string $key, int $ttl, callable $callback): mixed {
        $cache = self::getAdapter();

        $item = $cache->getItem($key);

        if ($item->isHit()) {
            return $item->get();
        }

        $value = $callback();

        if (!empty($value)) {
            $item->set($value);
            $item->expiresAfter($ttl);
            $cache->save($item);
        }

        return $value;
    }


    public static function clear(string $key): void {
        self::getAdapter()->deleteItem($key);
    }
    public static function clearAll(): void {
        self::getAdapter()->clear();
    }

}
