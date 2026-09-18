<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The WordPress URLs are indexed today and carry ranking. Letting them 404 discards it,
 * which is the single most expensive mistake available during a migration.
 */
final class RedirectsTest extends TestCase
{
    public static function oldUrls(): array
    {
        return [
            'about-us-2'    => ['/about-us-2', 'about'],
            'about-us-2/'   => ['/about-us-2/', 'about'],
            'about-us'      => ['/about-us', 'about'],
            'our-services'  => ['/our-services', 'services'],
            'our-services/' => ['/our-services/', 'services'],
            'contacts'      => ['/contacts', 'contact'],
            'contacts/'     => ['/contacts/', 'contact'],
            'faqs'          => ['/faqs', 'how-it-works'],
            'portfolio'     => ['/portfolio', 'services'],
            'shop'          => ['/shop', 'home'],
        ];
    }

    #[DataProvider('oldUrls')]
    public function test_old_wordpress_url_redirects_permanently(string $old, string $routeName): void
    {
        // 301 not 302: a temporary redirect tells a crawler to keep the OLD url, which is
        // the opposite of what a migration needs.
        $this->get($old)
            ->assertStatus(301)
            ->assertRedirect(route($routeName));
    }

    public function test_old_sitemaps_point_at_the_new_one(): void
    {
        foreach (['/sitemap_index.xml', '/wp-sitemap.xml'] as $old) {
            $this->get($old)->assertStatus(301)->assertRedirect(route('sitemap'));
        }
    }

    public function test_wordpress_infrastructure_paths_are_gone_not_redirected(): void
    {
        // 410 is the honest answer and stops well-behaved crawlers retrying. Redirecting
        // these to the homepage would be a soft 404 and keeps the bot traffic coming.
        foreach (['/wp-login.php', '/xmlrpc.php', '/wp-admin/', '/wp-admin/install.php', '/feed/'] as $path) {
            $this->get($path)->assertStatus(410);
        }
    }

    public function test_a_genuinely_unknown_url_still_404s(): void
    {
        // The redirect map must not become a catch-all that hides real broken links.
        $this->get('/no-such-page-anywhere')->assertNotFound();
    }
}
