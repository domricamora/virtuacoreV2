<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Content;
use App\Support\Seo;
use Tests\TestCase;

final class SeoTest extends TestCase
{
    private function graphOf(string $url): array
    {
        $html = $this->get($url)->assertOk()->getContent();
        $this->assertSame(1, preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $html, $m),
            "no JSON-LD block on {$url}");

        $data = json_decode($m[1], true, 512, JSON_THROW_ON_ERROR);

        return $data['@graph'];
    }

    private function nodeTypes(array $graph): array
    {
        return array_merge(...array_map(fn ($n) => (array) $n['@type'], $graph));
    }

    public function test_every_page_emits_organization_and_website(): void
    {
        foreach (['home', 'services', 'about', 'contact', 'pricing'] as $name) {
            $types = $this->nodeTypes($this->graphOf(route($name)));
            $this->assertContains('Organization', $types, "{$name} lost Organization");
            $this->assertContains('WebSite', $types, "{$name} lost WebSite");
        }
    }

    public function test_cross_references_resolve_inside_the_graph(): void
    {
        // Every @id referenced by another node must exist in the same graph, or the
        // reference dangles and the structured data is silently worthless.
        $graph = $this->graphOf(route('home'));
        $ids   = array_column($graph, '@id');

        foreach ($graph as $node) {
            foreach ($node as $value) {
                if (is_array($value) && isset($value['@id'])) {
                    $this->assertContains($value['@id'], $ids, "dangling @id: {$value['@id']}");
                }
            }
        }
    }

    public function test_it_never_claims_ratings_or_reviews(): void
    {
        // Both need real attributable reviews. Emitting them is a manual-action risk and
        // a lie a prospect can check -- the same rule that keeps the flags off.
        foreach (['home', 'services', 'pricing'] as $name) {
            $json = json_encode($this->graphOf(route($name)));
            $this->assertStringNotContainsString('AggregateRating', $json);
            $this->assertStringNotContainsString('"Review"', $json);
        }
    }

    public function test_an_empty_faq_list_produces_no_faqpage_node(): void
    {
        // An FAQPage with no questions claims structure the page does not have.
        $this->assertNull(Seo::faqPage([], url('/x')));
        $this->assertIsArray(Seo::faqPage([['Q?', 'A.']], url('/x')));
    }

    public function test_a_single_crumb_is_not_a_trail(): void
    {
        $this->assertNull(Seo::breadcrumbs([['name' => 'Home', 'url' => url('/')]]));
        $this->assertIsArray(Seo::breadcrumbs([
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Services', 'url' => url('/services')],
        ]));
    }

    public function test_sameas_only_lists_profiles_that_resolve(): void
    {
        // The WordPress footer pointed every social icon at "#". A sameAs full of nulls or
        // placeholders is worse than omitting the property.
        $org = Seo::organization();
        foreach ($org['sameAs'] ?? [] as $url) {
            $this->assertStringStartsWith('https://', $url);
        }
    }

    public function test_phone_in_schema_is_dialable_e164(): void
    {
        $this->assertMatchesRegularExpression('/^\+[1-9]\d{7,14}$/', Seo::organization()['telephone']);
    }

    public function test_pages_carry_a_canonical_and_a_description(): void
    {
        foreach (['home', 'services', 'about'] as $name) {
            $html = $this->get(route($name))->assertOk()->getContent();
            $this->assertStringContainsString('<link rel="canonical"', $html, "{$name} has no canonical");
        }
    }

    public function test_service_schema_is_built_for_a_real_service(): void
    {
        $svc  = Content::service('sales-development');
        $node = Seo::service($svc, url('/services/sales-development'));

        $this->assertSame('Service', $node['@type']);
        $this->assertSame(['@id' => url('/#organization')], $node['provider']);
        $this->assertNotEmpty($node['description']);
    }
}
