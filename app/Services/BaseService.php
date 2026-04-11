<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Base Service for all business logic services.
 * 
 * Provides standardized error handling, logging utilities,
 * cache helpers, and transaction management to ensure
 * consistency across all services in the application.
 */
abstract class BaseService
{
    /**
     * Execute a callback within a database transaction.
     *
     * @param callable $callback The callback to execute
     * @param int $attempts Number of retry attempts
     * @return mixed
     * @throws Exception
     */
    protected function transaction(callable $callback, int $attempts = 1): mixed
    {
        return DB::transaction($callback, $attempts);
    }

    /**
     * Execute a callback with retry logic.
     *
     * @param callable $callback The callback to execute
     * @param int $maxRetries Maximum number of retry attempts
     * @param int $delay Delay between retries in milliseconds
     * @return mixed
     * @throws Exception
     */
    protected function withRetry(callable $callback, int $maxRetries = 3, int $delay = 1000): mixed
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return $callback();
            } catch (Exception $e) {
                $lastException = $e;

                if ($attempt < $maxRetries) {
                    usleep($delay * 1000);
                    
                    $this->logWarning("Retry attempt {$attempt}/{$maxRetries}", [
                        'error' => $e->getMessage(),
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                    ]);
                }
            }
        }

        throw $lastException;
    }

    /**
     * Execute a callback with exponential backoff retry.
     *
     * @param callable $callback The callback to execute
     * @param int $maxRetries Maximum number of retry attempts
     * @param int $baseDelay Base delay in milliseconds
     * @return mixed
     * @throws Exception
     */
    protected function withExponentialBackoff(callable $callback, int $maxRetries = 3, int $baseDelay = 1000): mixed
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return $callback();
            } catch (Exception $e) {
                $lastException = $e;

                if ($attempt < $maxRetries) {
                    $exponentialDelay = $baseDelay * pow(2, $attempt - 1);
                    usleep($exponentialDelay * 1000);
                    
                    $this->logWarning("Exponential backoff retry attempt {$attempt}/{$maxRetries}", [
                        'error' => $e->getMessage(),
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                        'delay_ms' => $exponentialDelay,
                    ]);
                }
            }
        }

        throw $lastException;
    }

    /**
     * Get a value from cache.
     *
     * @param string $key The cache key
     * @param mixed $default Default value if not found
     * @param int|null $ttl Time to live in seconds (null = use default)
     * @return mixed
     */
    protected function cacheGet(string $key, mixed $default = null, ?int $ttl = null): mixed
    {
        return Cache::get($key, $default);
    }

    /**
     * Store a value in cache.
     *
     * @param string $key The cache key
     * @param mixed $value The value to cache
     * @param int $ttl Time to live in seconds
     * @return bool
     */
    protected function cachePut(string $key, mixed $value, int $ttl = 3600): bool
    {
        return Cache::put($key, $value, $ttl);
    }

    /**
     * Get a value from cache or execute callback and store result.
     *
     * @param string $key The cache key
     * @param callable $callback The callback to execute if not cached
     * @param int $ttl Time to live in seconds
     * @return mixed
     */
    protected function cacheRemember(string $key, callable $callback, int $ttl = 3600): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Get a value from cache or execute callback and store result forever.
     *
     * @param string $key The cache key
     * @param callable $callback The callback to execute if not cached
     * @return mixed
     */
    protected function cacheRememberForever(string $key, callable $callback): mixed
    {
        return Cache::rememberForever($key, $callback);
    }

    /**
     * Remove a value from cache.
     *
     * @param string $key The cache key
     * @return bool
     */
    protected function cacheForget(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Log an error with context.
     *
     * @param string $message The error message
     * @param array $context Additional context data
     * @param Exception|null $exception Optional exception to log
     * @return void
     */
    protected function logError(string $message, array $context = [], ?Exception $exception = null): void
    {
        $logContext = array_merge($context, [
            'service' => static::class,
            'exception' => $exception?->getMessage(),
            'trace' => $exception?->getTraceAsString(),
        ]);

        Log::error($message, $logContext);
    }

    /**
     * Log a warning with context.
     *
     * @param string $message The warning message
     * @param array $context Additional context data
     * @return void
     */
    protected function logWarning(string $message, array $context = []): void
    {
        $logContext = array_merge($context, [
            'service' => static::class,
        ]);

        Log::warning($message, $logContext);
    }

    /**
     * Log an info message with context.
     *
     * @param string $message The info message
     * @param array $context Additional context data
     * @return void
     */
    protected function logInfo(string $message, array $context = []): void
    {
        $logContext = array_merge($context, [
            'service' => static::class,
        ]);

        Log::info($message, $logContext);
    }

    /**
     * Log a debug message with context.
     *
     * @param string $message The debug message
     * @param array $context Additional context data
     * @return void
     */
    protected function logDebug(string $message, array $context = []): void
    {
        $logContext = array_merge($context, [
            'service' => static::class,
        ]);

        Log::debug($message, $logContext);
    }

    /**
     * Create a standardized result array.
     *
     * @param bool $success Whether the operation was successful
     * @param string $message Result message
     * @param mixed $data Result data
     * @return array
     */
    protected function createResult(bool $success, string $message, mixed $data = null): array
    {
        $result = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $result['data'] = $data;
        }

        return $result;
    }

    /**
     * Create a success result array.
     *
     * @param string $message Success message
     * @param mixed $data Result data
     * @return array
     */
    protected function successResult(string $message = 'Operation successful', mixed $data = null): array
    {
        return $this->createResult(success: true, message: $message, data: $data);
    }

    /**
     * Create an error result array.
     *
     * @param string $message Error message
     * @param mixed $data Error data
     * @return array
     */
    protected function errorResult(string $message = 'Operation failed', mixed $data = null): array
    {
        return $this->createResult(success: false, message: $message, data: $data);
    }

    /**
     * Safely execute a callback and return a default value on failure.
     *
     * @param callable $callback The callback to execute
     * @param mixed $defaultValue Default value to return on failure
     * @param string|null $logMessage Optional log message on failure
     * @return mixed
     */
    protected function safely(callable $callback, mixed $defaultValue = null, ?string $logMessage = null): mixed
    {
        try {
            return $callback();
        } catch (Exception $e) {
            $message = $logMessage ?? 'Safe execution failed';
            $this->logError($message, [], $e);
            
            return $defaultValue;
        }
    }
}
