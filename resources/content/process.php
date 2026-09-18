<?php

declare(strict_types=1);

/**
 * The four stages of an engagement, in the order they actually happen.
 *
 * Numbered in the UI because this IS a sequence. Numbering non-sequential content is one
 * of the tells that a page was assembled rather than written.
 *
 * Ported verbatim from the source build's lib/Content.php. Content is data, never
 * markup: views read this, they do not restate it.
 */
return [
    [
        'step' => 'Scope',
        'title' => 'We write down the job before we look for anyone',
        'body' => 'A 30-minute call produces a written scope: the tasks, the hours, the tools, the coverage window and the number that says it is working.',
        'detail' => [
            'Tasks listed specifically, not as a job title',
            'Coverage hours fixed in writing',
            'Success measure agreed up front',
        ],
    ],
    [
        'step' => 'Shortlist',
        'title' => 'You interview three, not thirty',
        'body' => 'We screen against the scope, test the skills that matter for it, and send you a shortlist of three with our notes and our reservations.',
        'detail' => [
            'Skills tested, not self-reported',
            'English proficiency assessed in conversation',
            'Our reservations included, not hidden',
        ],
    ],
    [
        'step' => 'Onboard',
        'title' => 'A structured first week, not a login and good luck',
        'body' => 'Access, tone guide, escalation rules and process documentation are deliverables of week one. You approve them before normal work starts.',
        'detail' => [
            'Least-privilege access, revocable and audited',
            'Written tone and escalation guides',
            'Process documented as it is learned',
        ],
    ],
    [
        'step' => 'Run',
        'title' => 'Weekly reporting, and a replacement if it is wrong',
        'body' => 'You get a weekly number against the measure agreed in step one. If the person is not right, replacing them is our cost, not yours.',
        'detail' => [
            'Weekly reporting against the agreed measure',
            'Named replacement guarantee',
            'No long lock-in',
        ],
    ],
];
