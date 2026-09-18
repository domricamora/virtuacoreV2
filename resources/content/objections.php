<?php

declare(strict_types=1);

/**
 * The questions every prospect asks, answered plainly.
 *
 * Drives the FAQ section and the FAQPage schema.
 *
 * Ported verbatim from the source build's lib/Content.php. Content is data, never
 * markup: views read this, they do not restate it.
 */
return [
    [
        'q' => 'What about the timezone gap?',
        'a' => 'Coverage hours are fixed in the written scope before anyone starts, not negotiated afterwards. Most clients run full US business-hours cover. Where a full match is not needed, we set a specific overlap window instead of leaving it vague.',
    ],
    [
        'q' => 'How do I know the quality is there?',
        'a' => 'Skills are tested rather than self-reported, and you interview a shortlist of three with our written notes and our reservations included. If the placement is wrong, replacing them is our cost, because the screening was ours to get right.',
    ],
    [
        'q' => 'Is my data safe?',
        'a' => 'Access is least-privilege and delegated rather than shared passwords, so it is revocable instantly and leaves an audit trail. Your data stays in your systems under your ownership. We do not hold client data in an intermediate tool.',
    ],
    [
        'q' => 'Will managing them become another job?',
        'a' => 'That is the failure mode we design against. The written scope, the documented process and the weekly number exist so the engagement runs on reporting rather than supervision. If you are chasing daily, something in the scope was wrong.',
    ],
];
