<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Content;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class RoutesTest extends TestCase
{
    public static function namedRoutes(): array
    {
        return [
            'home'         => ['home'],
            'services'     => ['services'],
            'how it works' => ['how-it-works'],
            'pricing'      => ['pricing'],
            'for authors'  => ['for-authors'],
            'about'        => ['about'],
            'contact'      => ['contact'],
            'privacy'      => ['privacy'],
            'terms'        => ['terms'],
        ];
    }

    #[DataProvider('namedRoutes')]
    public function test_named_route_responds(string $name): void
    {
        $this->get(route($name))->assertOk();
    }

    public function test_every_service_has_a_reachable_detail_page(): void
    {
        foreach (array_keys(Content::services()) as $slug) {
            $this->get(route('services.show', $slug))->assertOk();
        }
        foreach (array_keys(Content::authorServices()) as $slug) {
            $this->get(route('authors.show', $slug))->assertOk();
        }
    }

    public function test_an_unknown_service_slug_is_a_404_from_routing(): void
    {
        // whereIn() constrains the slug to keys that exist, so an unknown one never
        // reaches a view and cannot null-dereference the content lookup.
        $this->get('/services/not-a-real-service')->assertNotFound();
        $this->get('/for-authors/not-a-real-service')->assertNotFound();
    }

    public function test_every_page_carries_the_nav_and_exactly_one_h1(): void
    {
        foreach (self::namedRoutes() as [$name]) {
            $html = $this->get(route($name))->assertOk()->getContent();

            $this->assertStringContainsString('vc-nav__bar', $html, "{$name} is missing the nav");
            $this->assertSame(
                1,
                substr_count($html, '<h1'),
                "{$name} must have exactly one h1"
            );
        }
    }

    public function test_internal_links_all_resolve(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        // APP_URL carries a port in this environment, so the host pattern must tolerate
        // one. Matching a bare host captured ":8000/services" as the path and reported
        // every link as broken.
        $host = preg_quote(rtrim(config('app.url'), '/'), '~');
        preg_match_all('~href="'.$host.'([^"#]*)"~', $html, $m);

        // Asset URLs (icon sprite, css, js) are files served by the web server rather than
        // routes, so they are unreachable through the test kernel. Only page paths are
        // meaningful here, and those carry no file extension.
        $paths = array_values(array_unique(array_filter(
            $m[1],
            fn (string $p) => ! str_contains(basename($p), '.')
        )));

        $this->assertNotEmpty($paths, 'no internal page links found on the homepage');

        // Collect every failure rather than dying on the first: assertOk() discards a
        // custom message, so a bare loop reports "expected 200, got 404" without ever
        // naming the link that broke.
        $broken = [];
        foreach ($paths as $path) {
            $status = $this->get($path === '' ? '/' : $path)->getStatusCode();
            if ($status !== 200) {
                $broken[] = ($path === '' ? '/' : $path)." -> {$status}";
            }
        }

        $this->assertSame([], $broken, "broken internal links:
  ".implode("
  ", $broken));
    }
}
