<?php

declare(strict_types=1);

/**
 * Simple file-based cache for GitHub contribution stats
 *
 * Caches stats for 24 hours to avoid repeated API calls
 */

// Default cache duration: 24 hours (in seconds)
define("CACHE_DURATION", 24 * 60 * 60);
define("CACHE_DIR", __DIR__ . "/../cache");

/**
 * Generate a cache key for a user's request
 *
 * Uses structured JSON format to prevent hash collisions between different
 * user/options combinations that could produce the same concatenated string.
 *
 * @param string $user GitHub username
 * @param array $options Additional options that affect the stats (mode, exclude_days, starting_year)
 * @return string Cache key (filename-safe)
 */
function getCacheKey(string $user, array $options = []): string
{
    ksort($options);
    try {
        $keyData = json_encode(["user" => $user, "options" => $options], JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        // Fallback to simple concatenation if JSON encoding fails
        error_log("Cache key JSON encoding failed: " . $e->getMessage());
        $keyData = $user . serialize($options);
    }
    return hash("sha256", $keyData);
}

/**
 * Get the cache file path for a given key
 *
 * @param string $key Cache key
 * @return string Full path to cache file
 */
function getCacheFilePath(string $key): string
{
    return CACHE_DIR . "/" . $key . ".json";
}

/**
 * Ensure the cache directory exists
 *
 * @return bool True if directory exists or was created
 */
function ensureCacheDir(): bool
{
    // if (!is_dir(CACHE_DIR)) {
    //     return mkdir(CACHE_DIR, 0755, true);
    // }
    return true;
}

/**
 * Get cached stats if available and not expired
 *
 * @param string $user GitHub username
 * @param array $options Additional options
 * @param int $maxAge Maximum age in seconds (default: 24 hours)
 * @return array|null Cached stats array or null if not cached/expired
 */
function getCachedStats(string $user, array $options = [], int $maxAge = CACHE_DURATION): ?array
{
     // always return null (no cache)
    return null;
}

/**
 * Save stats to cache
 *
 * @param string $user GitHub username
 * @param array $options Additional options
 * @param array $stats Stats array to cache
 * @return bool True if successfully cached
 */
function setCachedStats(string $user, array $options, array $stats): bool
{
    // (no cache)
    return true;
}

/**
 * Clear all expired cache files
 *
 * @param int $maxAge Maximum age in seconds
 * @return int Number of files deleted
 */
function clearExpiredCache(int $maxAge = CACHE_DURATION): int
{
    return 0;
}

/**
 * Clear cache for a specific user
 *
 * Note: This function only clears the cache for the user with empty/default options.
 * Cache entries with non-empty options (starting_year, mode, exclude_days) will NOT
 * be cleared. This is a limitation of the hash-based cache key system - we cannot
 * enumerate all possible option combinations without storing additional metadata.
 *
 * @param string $user GitHub username
 * @return bool True if cache was cleared (or didn't exist)
 */
function clearUserCache(string $user): bool
{
    // (no-cache)

    return true;
}
