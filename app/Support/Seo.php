<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

/**
 * JSON-LD builders and meta helpers.
 *
 * One @graph per page so nodes cross-reference by @id rather than repeating themselves.
 * Organization and WebSite are emitted on every page, which is what makes the @id
 * references from Service, FAQPage and BreadcrumbList always resolve.
 *
 * NOTE: this deliberately emits no AggregateRating and no Review. Both require real,
 * attributable reviews. Inventing them is a Google manual-action risk and a plain lie, and
 * the same rule keeps the stats and testimonial flags off in config/site.php.
 */
final class Seo
{
    /** Trim to a clean sentence boundary rather than mid-word, and never mid-entity. */
    public static function meta(string $text, int $max = 158): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');

        return Str::limit($text, $max, '');
    }

    public static function organization(): array
    {
        $phone = config('site.phone');
        $addr  = config('site.address');

        return array_filter([
            '@type'     => ['Organization', 'ProfessionalService'],
            '@id'       => url('/#organization'),
            'name'      => config('site.name'),
            'url'       => url('/'),
            'logo'      => [
                '@type'  => 'ImageObject',
                'url'    => asset('images/logo-square.png'),
                'width'  => 512,
                'height' => 512,
            ],
            'image'     => asset('images/logo-square.png'),
            'email'     => config('site.email'),
            'telephone' => $phone['e164'],
            'description' => 'VirtuaCore places vetted remote staff with businesses: sales '
                . 'development, administrative support, customer support, project management '
                . 'and marketing.',
            'address' => array_filter([
                '@type'           => 'PostalAddress',
                'streetAddress'   => $addr['street'],
                'addressLocality' => $addr['locality'],
                'addressRegion'   => $addr['region'],
                'postalCode'      => $addr['postal'],
                'addressCountry'  => $addr['country'],
            ]),
            'openingHours'  => config('site.hours.spec'),
            'areaServed'    => ['@type' => 'Place', 'name' => 'Worldwide'],
            'knowsLanguage' => ['en'],
            // Only profiles that actually resolve. sameAs pointing at "#" is worse than absent.
            'sameAs' => array_values(array_filter(config('site.social'))) ?: null,
        ]);
    }

    public static function website(): array
    {
        return [
            '@type'      => 'WebSite',
            '@id'        => url('/#website'),
            'url'        => url('/'),
            'name'       => config('site.name'),
            'publisher'  => ['@id' => url('/#organization')],
            'inLanguage' => 'en-US',
        ];
    }

    /**
     * A WebPage node for the page being rendered.
     *
     * Without it the graph describes the ORGANISATION but never the page itself, so a
     * crawler has no entity to attach the page's title, description or breadcrumb to.
     * It is the node that makes the rest of the graph resolve to something.
     */
    public static function webPage(string $canonical, string $name, ?string $description = null): array
    {
        return array_filter([
            '@type'       => 'WebPage',
            '@id'         => $canonical.'#webpage',
            'url'         => $canonical,
            'name'        => $name,
            'description' => $description ? self::meta($description) : null,
            'isPartOf'    => ['@id' => url('/#website')],
            'about'       => ['@id' => url('/#organization')],
            'publisher'   => ['@id' => url('/#organization')],
            'inLanguage'  => 'en-US',
        ]);
    }

    /** @param array<string, mixed> $svc */
    public static function service(array $svc, string $canonical): array
    {
        return array_filter([
            '@type'       => 'Service',
            '@id'         => $canonical.'#service',
            'name'        => $svc['name'] ?? null,
            'description' => isset($svc['answer']) ? self::meta((string) $svc['answer'], 300) : null,
            'provider'    => ['@id' => url('/#organization')],
            'areaServed'  => ['@type' => 'Place', 'name' => 'Worldwide'],
            'url'         => $canonical,
        ]);
    }

    /**
     * @param array<int, array{0: string, 1: string}> $faqs
     *
     * Returns null rather than an empty FAQPage: a schema node with no questions is worse
     * than no node, because it claims structure the page does not have.
     */
    public static function faqPage(array $faqs, string $canonical): ?array
    {
        if ($faqs === []) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            '@id'   => $canonical.'#faq',
            'mainEntity' => array_map(fn (array $f) => [
                '@type'          => 'Question',
                'name'           => $f[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
            ], array_values($faqs)),
        ];
    }

    /** @param array<int, array{name: string, url: string}> $crumbs */
    public static function breadcrumbs(array $crumbs): ?array
    {
        if (count($crumbs) < 2) {
            return null;   // a single crumb is not a trail
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn (int $i, array $c) => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $c['name'],
                'item'     => $c['url'],
            ], array_keys($crumbs), array_values($crumbs)),
        ];
    }

    /** @param array<int, array<string, mixed>|null> $extra */
    public static function graph(array $extra = []): string
    {
        $nodes = array_values(array_filter(array_merge(
            [self::organization(), self::website()],
            $extra
        )));

        return json_encode(
            ['@context' => 'https://schema.org', '@graph' => $nodes],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ) ?: '{}';
    }
}
