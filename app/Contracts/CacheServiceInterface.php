<?php

namespace App\Contracts;

/**
 * Interface for cache service implementations.
 * 
 * Provides a standardized contract for caching operations
 * to enable different cache strategies (Redis, database, file, etc.)
 */
interface CacheServiceInterface
{
    /**
     * Get an item from the cache.
     *
     * @param string $key The cache key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Store an item in the cache.
     *
     * @param string $key The cache key
     * @param mixed $value The value to store
     * @param int|null $ttl Time to live in seconds
     * @return bool
     */
    public function put(string $key, mixed $value, ?int $ttl = null): bool;

    /**
     * Get an item from cache or store the result of callback.
     *
     * @param string $key The cache key
     * @param callable $callback The callback to execute
     * @param int|null $ttl Time to live in seconds
     * @return mixed
     */
    public function remember(string $key, callable $callback, ?int $ttl = null): mixed;

    /**
     * Remove an item from the cache.
     *
     * @param string $key The cache key
     * @return bool
     */
    public function forget(string $key): bool;

    /**
     * Clear all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool;

    /**
     * Check if an item exists in the cache.
     *
     * @param string $key The cache key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Increment a value in the cache.
     *
     * @param string $key The cache key
     * @param int $value The increment amount
     * @return int|bool
     */
    public function increment(string $key, int $value = 1): int|bool;

    /**
     * Decrement a value in the cache.
     *
     * @param string $key The cache key
     * @param int $value The decrement amount
     * @return int|bool
     */
    public function decrement(string $key, int $value = 1): int|bool;

    /**
     * Get multiple items from the cache.
     *
     * @param array $keys Array of cache keys
     * @return array
     */
    public function getMultiple(array $keys): array;

    /**
     * Store multiple items in the cache.
     *
     * @param array $values Array of key-value pairs
     * @param int|null $ttl Time to live in seconds
     * @return bool
     */
    public function putMultiple(array $values, ?int $ttl = null): bool;

    /**
     * Remove multiple items from the cache.
     *
     * @param array $keys Array of cache keys
     * @return bool
     */
    public function forgetMultiple(array $keys): bool;

    /**
     * Get cache statistics.
     *
     * @return array
     */
    public function getStats(): array;

    /**
     * Check if the cache service is available.
     *
     * @return bool
     */
    public function isAvailable(): bool;
}
