<?php

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

require_once __DIR__ . '/../vendor/autoload.php';

class CacheMiddleware
{
    private $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public function handle(string $key, int $ttl, callable $callback): string
    {
        $fromCache = true;

        $content = $this->cache->get($key, function (ItemInterface $item) use ($ttl, $callback, &$fromCache) {
            $fromCache = false;
            $item->expiresAfter($ttl);
            ob_start();
            $callback();
            return ob_get_clean();
        });

        $debugNote = $fromCache
            ? "\n<!-- Caricato dalla cache -->"
            : "\n<!-- Caricato dal database -->";

        return $content . $debugNote;
    }

    public function clear(string $key)
    {
        $this->cache->deleteItem($key);
    }
}
