<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Content;
use Tests\TestCase;

final class SeoEndpointsTest extends TestCase
{
    public function test_the_sitemap_lists_every_public_page_and_nothing_else(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=utf-8')
            ->getContent();

        $doc = simplexml_load_string($xml);
        $this->assertNotFalse($doc, 'the sitemap is not valid XML');

        // iterator_to_array() PRESERVES KEYS, and every SimpleXML child here shares the
        // key "url" -- so the default collapses all 20 entries into one, keeping the last.
        // The false flag discards keys. Without it this assertion passes vacuously.
        $locs = array_map(fn ($u) => (string) $u->loc, iterator_to_array($doc->url, false));

        $this->assertGreaterThan(15, count($locs), 'the sitemap collapsed to too few urls');

        foreach (['home','services','how-it-works','pricing','for-authors','about','contact','privacy','terms'] as $name) {
            $this->assertContains(route($name), $locs, "sitemap is missing {$name}");
        }
        foreach (array_keys(Content::services()) as $slug) {
            $this->assertContains(route('services.show', $slug), $locs);
        }
        foreach (array_keys(Content::authorServices()) as $slug) {
            $this->assertContains(route('authors.show', $slug), $locs);
        }

        $this->assertSame($locs, array_unique($locs), 'the sitemap contains duplicates');
    }

    public function test_the_sitemap_is_not_itself_indexable(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertHeader('X-Robots-Tag', 'noindex');
    }

    public function test_robots_points_at_the_canonical_sitemap_host(): void
    {
        // The source build's static robots.txt named the non-www host while every canonical
        // used www. Generating the line removes the chance of that drift.
        $txt = $this->get('/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString('Sitemap: '.route('sitemap'), $txt);
    }

    public function test_llms_carries_the_caveats_an_assistant_must_inherit(): void
    {
        $txt = $this->get('/llms.txt')->assertOk()->getContent();

        $this->assertStringContainsString('does not claim', $txt);
        $this->assertStringContainsString('guarantees an adaptation', $txt);
        $this->assertStringContainsString('No rates are published', $txt);

        // Every service must appear, or an assistant summarising the site will omit one.
        foreach (Content::allServices() as $svc) {
            $this->assertStringContainsString($svc['name'], $txt, "llms.txt omits {$svc['name']}");
        }
    }
}
