<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Signals that make pages legible to search engines and AI assistants.
 *
 * These are all things that fail silently: a page with no WebPage node, a duplicated
 * share image or an over-long description all render perfectly and simply perform worse.
 */
final class SeoSignalsTest extends TestCase
{
    public static function pages(): array
    {
        return [
            'home' => ['home'], 'services' => ['services'], 'for authors' => ['for-authors'],
            'how it works' => ['how-it-works'], 'pricing' => ['pricing'],
            'about' => ['about'], 'contact' => ['contact'],
        ];
    }

    private function graph(string $route): array
    {
        $html = $this->get(route($route))->assertOk()->getContent();
        preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $html, $m);

        return json_decode($m[1], true, 512, JSON_THROW_ON_ERROR)['@graph'];
    }

    #[DataProvider('pages')]
    public function test_every_page_describes_itself_as_a_webpage(string $route): void
    {
        // Without a WebPage node the graph describes the ORGANISATION but never the page,
        // so a crawler has no entity to attach this page's title or breadcrumb to.
        $types = [];
        foreach ($this->graph($route) as $n) {
            $t = $n['@type'] ?? null;
            $types = array_merge($types, is_array($t) ? $t : [$t]);
        }
        $this->assertContains('WebPage', $types, "{$route} has no WebPage node");
    }

    #[DataProvider('pages')]
    public function test_the_webpage_node_links_back_to_the_site_and_organisation(string $route): void
    {
        $page = null;
        foreach ($this->graph($route) as $n) {
            if (($n['@type'] ?? null) === 'WebPage') { $page = $n; }
        }
        $this->assertNotNull($page);
        $this->assertSame(['@id' => url('/#website')], $page['isPartOf']);
        $this->assertSame(['@id' => url('/#organization')], $page['publisher']);
        $this->assertSame(route($route), $page['url']);
    }

    #[DataProvider('pages')]
    public function test_meta_description_is_within_the_useful_range(string $route): void
    {
        // Under ~70 chars wastes the slot; over ~160 gets truncated mid-sentence in results.
        $html = $this->get(route($route))->assertOk()->getContent();
        preg_match('~<meta name="description" content="([^"]*)"~', $html, $m);

        $len = strlen($m[1] ?? '');
        $this->assertGreaterThan(70, $len, "{$route} description is too short ({$len})");
        $this->assertLessThanOrEqual(160, $len, "{$route} description will be truncated ({$len})");
    }

    public function test_share_images_are_not_all_the_same(): void
    {
        // One shared image makes every link preview identical, which throws away a signal
        // the pages already have, since each carries its own hero poster.
        $seen = [];
        foreach (self::pages() as [$route]) {
            $html = $this->get(route($route))->assertOk()->getContent();
            preg_match('~<meta property="og:image" content="([^"]*)"~', $html, $m);
            $this->assertNotEmpty($m[1] ?? '', "{$route} has no og:image");
            $seen[] = basename($m[1]);
        }
        $this->assertGreaterThan(5, count(array_unique($seen)),
            'share images are largely duplicated: '.implode(', ', array_unique($seen)));
    }

    #[DataProvider('pages')]
    public function test_pages_carry_canonical_locale_and_twitter_card(string $route): void
    {
        $html = $this->get(route($route))->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="'.route($route).'"', $html);
        $this->assertStringContainsString('<meta property="og:locale" content="en_US">', $html);
        $this->assertStringContainsString('name="twitter:card" content="summary_large_image"', $html);
    }

    public function test_robots_points_ai_crawlers_at_the_brief_written_for_them(): void
    {
        $txt = $this->get('/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString(route('llms'), $txt);
        foreach (['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Google-Extended'] as $bot) {
            $this->assertStringContainsString('User-agent: '.$bot, $txt);
        }
    }
}
