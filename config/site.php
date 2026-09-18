<?php

declare(strict_types=1);

/**
 * Every owner-editable global lives here. Views never hardcode these.
 *
 * The WordPress site displayed +1-307-333-8809 but linked tel:+1-800-456-478-23, an
 * Elementor theme placeholder that is not a valid E.164 number and cannot be dialled.
 * Keeping the display string and the link as two views of ONE value is what stops that
 * class of bug returning.
 */
return [
    'name'    => 'VirtuaCore',
    'tagline' => 'Virtual Support, Real Results',

    'phone' => [
        'display' => '+1-307-333-8809',
        'e164'    => '+13073338809',
    ],

    'email' => 'info@virtuacore.net',

    /**
     * Where enquiry notifications go. Both addresses are on the site's own domain, so
     * delivery is local and does not depend on an external SMTP provider.
     *
     * The lead is written to storage/logs/leads.log BEFORE any send is attempted, so a
     * mail failure degrades to "nobody was notified" rather than "the enquiry is gone".
     */
    'lead_recipients' => [
        'admin@virtuacore.net',
        'info@virtuacore.net',
    ],

    'address' => [
        'street'   => '30 N Gould St Ste N',
        'locality' => 'Sheridan',
        'region'   => 'WY',
        'postal'   => '82801',
        'country'  => 'US',
    ],

    /**
     * The WordPress site said "Mon - Sat: 8.00 - 18.00" with no zone, which is ambiguous
     * for a company selling coverage hours. The source build resolved it to MT, which
     * matches the Sheridan, Wyoming address. Stated explicitly on the page: a prospect in
     * another zone cannot act on an unqualified time.
     */
    'hours' => [
        'days'  => 'Mon to Sat',
        'open'  => '8:00',
        'close' => '18:00',
        'tz'    => 'MT',
        'iana'  => 'America/Denver',
        // schema.org openingHours format
        'spec'  => 'Mo-Sa 08:00-18:00',
    ],

    // Only URLs that actually resolve belong here. The WordPress footer pointed every
    // social icon at "#", which is worse than omitting them.
    'social' => [
        'facebook'  => 'https://www.facebook.com/virtuacoreVA/',
        'instagram' => 'https://www.instagram.com/virtuacore_llc/',
        'linkedin'  => null,   // TODO(owner)
        'x'         => null,   // TODO(owner)
    ],

    'google_site_verification' => 'ttmbkDUsHXydqb42AtnapaihDHQ1Imb7Oc3TB6_0bw0',

    /**
     * Nothing on this site claims something that is not true.
     *
     * The WordPress build rendered every counter as a literal 0 (Support Given: 0,
     * Clients Rating: 0+, Awards Won: 0) and carried five testimonials that read as
     * placeholder names over stock headshots. A zeroed counter says "no clients"; an
     * invented review is a lie a prospect can check. Turn a flag on only when there is
     * real, attributable data to put behind it.
     */
    /**
     * Legal pages state a date. It is a config value rather than the view file's mtime so
     * that a deploy touching the template does not silently claim the policy changed.
     * Update it when the POLICY changes.
     */
    'legal' => [
        'updated'    => '2026-09-18',
        'legal_name' => 'VirtuaCore LLC',
    ],

    'features' => [
        'stats'        => false,  // needs real figures
        'testimonials' => false,  // names+quotes are real, photos are not; initials avatars pending
        'logo_wall'    => false,
        // Own-server analytics. OFF until the tracking layer is ported: the privacy policy
        // is written from this flag, and a policy describing collection that does not
        // happen is as wrong as one that omits collection that does.
        'analytics'    => false,  // the six SVGs on the old site are Elementor demo logos
    ],
];
