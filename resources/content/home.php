<?php

declare(strict_types=1);

/**
 * Homepage sections that are neither services nor process: the outcome row, the four
 * pillars, and the closing reassurance list.
 *
 * Lifted out of the index template during the Laravel port, where all three were
 * hardcoded arrays inside markup.
 *
 * Every line here is a promise the business has to keep. They are the first claims a
 * visitor reads and the easiest to check, so none of them may drift into something
 * aspirational.
 */
return [
    'outcomes' => [
        ['icon' => 'clock',          'title' => 'Your week back',    'body' => 'The recurring work leaves your desk in the first fortnight.'],
        ['icon' => 'seal-check',     'title' => 'Vetted on skills',  'body' => 'Tested against the scope, not taken on trust from a CV.'],
        ['icon' => 'handshake',      'title' => 'Replaced if wrong', 'body' => 'A bad match is our cost to fix, because the screening was ours.'],
        ['icon' => 'chart-line-up',  'title' => 'A weekly number',   'body' => 'Reporting against the measure agreed before anyone started.'],
    ],

    /**
     * Numbered 01-04 in the UI. That is defensible here because these ARE sequential:
     * they follow the engagement in the order it happens, scope before screening before
     * access before the guarantee. Do not reuse this treatment on unordered content.
     */
    'pillars' => [
        [
            'title' => 'A scope in writing before we look for anyone',
            'body'  => 'Tasks, hours, tools, coverage window and the number that says it is working. Agreed on a 30-minute call and written down, so the role can be judged rather than felt.',
        ],
        [
            'title' => 'Skills tested, not self-reported',
            'body'  => 'We screen against that scope and test the skills it actually needs. You interview three people, with our notes and our reservations included rather than hidden.',
        ],
        [
            'title' => 'Least-privilege access from day one',
            'body'  => 'Delegated access rather than shared passwords: revocable instantly, and it leaves an audit trail. Your data stays in your systems, under your ownership.',
        ],
        [
            'title' => 'A replacement guarantee that costs you nothing',
            'body'  => 'If the placement is wrong, we replace them. The screening was ours to get right, so fixing it is our cost rather than something you absorb.',
        ],
    ],

    'cta_points' => [
        'A written scope you keep either way',
        'Three vetted candidates, with our reservations',
        'No lock-in and no placement fee up front',
    ],
];
