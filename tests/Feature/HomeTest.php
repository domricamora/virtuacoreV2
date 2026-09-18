<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Content;
use Tests\TestCase;

final class HomeTest extends TestCase
{
    private function html(): string
    {
        return $this->get(route('home'))->assertOk()->getContent();
    }

    public function test_every_homepage_section_renders(): void
    {
        $html = $this->html();

        foreach ([
            'Five roles that carry a business day',
            'Most outsourcing fails on the setup, not the person',
            'From first call to someone working',
            'The four questions everyone asks',
            'Publishing and digital media, for writers rather than businesses',
            'Start with the scope, not the hire',
        ] as $heading) {
            $this->assertStringContainsString($heading, $html, "homepage lost: {$heading}");
        }
    }

    public function test_all_content_driven_rows_render_every_item(): void
    {
        $html = $this->html();
        $home = Content::home();

        foreach ($home['outcomes'] as $o) {
            $this->assertStringContainsString(e($o['title']), $html, "outcome missing: {$o['title']}");
        }
        foreach ($home['pillars'] as $p) {
            $this->assertStringContainsString(e($p['title']), $html, "pillar missing: {$p['title']}");
        }
        foreach ($home['cta_points'] as $point) {
            $this->assertStringContainsString(e($point), $html, "cta point missing: {$point}");
        }
        foreach (Content::services() as $svc) {
            $this->assertStringContainsString(e($svc['name']), $html, "service missing: {$svc['name']}");
        }
        foreach (Content::process() as $step) {
            $this->assertStringContainsString(e($step['title']), $html, "process step missing");
        }
    }

    public function test_the_faq_accordion_and_its_schema_agree(): void
    {
        // Visible Q&A without the schema wastes it; schema without the visible Q&A is a
        // structured-data violation Google penalises. They must move together.
        $html = $this->html();

        preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $html, $m);
        $graph = json_decode($m[1], true, 512, JSON_THROW_ON_ERROR)['@graph'];

        $faqNode = null;
        foreach ($graph as $node) {
            if (($node['@type'] ?? null) === 'FAQPage') {
                $faqNode = $node;
            }
        }

        $this->assertNotNull($faqNode, 'the homepage renders an FAQ but emits no FAQPage');
        $this->assertCount(count(Content::objections()), $faqNode['mainEntity']);

        foreach (Content::objections() as $o) {
            $this->assertStringContainsString(e($o['q']), $html, "FAQ question not visible: {$o['q']}");
        }
    }

    public function test_the_hero_video_never_loads_eagerly(): void
    {
        // Sources stay in data-src until the JS gate passes. A plain src here would mean
        // every visitor fetches 1.9 MB regardless of motion preference or connection.
        $html = $this->html();

        $this->assertStringContainsString('preload="none"', $html);
        $this->assertStringContainsString('data-src=', $html);
        $this->assertDoesNotMatchRegularExpression(
            '~<source\s[^>]*\ssrc=~',
            $html,
            'a hero <source> has a live src -- the loading gate is bypassed'
        );
    }

    public function test_the_hero_offers_a_pause_control(): void
    {
        // WCAG 2.2.2: content moving for more than five seconds needs a way to stop it.
        $this->assertStringContainsString('data-hero-toggle', $this->html());
    }
}
