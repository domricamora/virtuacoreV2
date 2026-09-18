<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

/**
 * The single source of truth for page content.
 *
 * Views read through this; they never require a content file directly and never restate
 * copy inline. One entry in resources/content/services.php produces a service page, its
 * nav entry, its footer link, its sitemap row, its llms.txt section, its lead-form option
 * and its schema — so the data cannot drift out of sync with the pages that present it.
 *
 * Files are loaded once per request and memoised. They are plain `return [...]` arrays
 * with no side effects, which is what makes that safe.
 */
final class Content
{
    /** @var array<string, array<mixed>> */
    private static array $cache = [];

    /** @return array<string, mixed> */
    public static function get(string $set): array
    {
        if (isset(self::$cache[$set])) {
            return self::$cache[$set];
        }

        // Whitelist rather than interpolate: $set reaching the filesystem from a route
        // parameter is how a content lookup becomes a file-disclosure bug.
        if (! preg_match('/^[a-z][a-z0-9-]*$/', $set)) {
            throw new InvalidArgumentException("Invalid content set: {$set}");
        }

        $path = resource_path("content/{$set}.php");

        if (! is_file($path)) {
            throw new InvalidArgumentException("No content set at {$path}");
        }

        return self::$cache[$set] = require $path;
    }

    /** @return array<string, array<string, mixed>> */
    public static function services(): array
    {
        return self::get('services');
    }

    /** @return array<string, array<string, mixed>> */
    public static function authorServices(): array
    {
        return self::get('author-services');
    }

    /** @return array<int, array<string, mixed>> */
    public static function process(): array
    {
        return self::get('process');
    }

    /** @return array<int, array<string, mixed>> */
    public static function objections(): array
    {
        return self::get('objections');
    }

    /** @return array<int, array<string, mixed>> */
    public static function weekOne(): array
    {
        return self::get('week-one');
    }

    /** @return array{models: array, drivers: array, faqs: array} */
    public static function pricing(): array
    {
        return self::get('pricing');
    }

    /** @return array{story: array, mission: array, vision: array, departments: array} */
    public static function company(): array
    {
        return self::get('company');
    }

    /** @return array<string, mixed>|null */
    public static function service(string $slug): ?array
    {
        return self::services()[$slug] ?? null;
    }

    /** @return array<string, mixed>|null */
    public static function authorService(string $slug): ?array
    {
        return self::authorServices()[$slug] ?? null;
    }

    /** Both lines, for the places that present them together (sitemap, llms.txt, footer). */
    public static function allServices(): array
    {
        return self::services() + self::authorServices();
    }

    /** Test seam: content is memoised for the life of the request. */
    public static function flush(): void
    {
        self::$cache = [];
    }
}
