<?php

declare(strict_types=1);

/**
 * Company narrative: the story, mission, vision and the three departments.
 *
 * Lifted out of the about template during the Laravel port, where the departments array
 * was hardcoded markup. Content is data; the page renders this rather than restating it.
 */
return [
    'story' => [
        'Giving people room to work is not a perk, it is how anything gets done without the owner in the middle of it. But freedom without structure gets messy fast, and the mess always lands back on the person who was trying to delegate.',
        'So we do the structure. We handpick specialists who can handle the heavy lifting, then write down what the job is, what they decide, what they escalate, and what number says it is working. That is the difference between a hire you can trust and a hire you have to supervise.',
        'From lead generation to closing deals, and from phone calls to email and chat, the work runs on a scope you approved rather than on guesswork.',
    ],

    'mission' => [
        'title' => 'What we are here to do',
        'body'  => 'Empower business owners, entrepreneurs and writers with first-rate virtual support: knowledgeable guidance, dependable delivery, and a standard we hold rather than advertise. So clients can build their brands and run their operations without doing every part of it themselves.',
    ],

    'vision' => [
        'title' => 'Where we are going',
        'body'  => 'A working week where nobody has to do it all alone. We want to be the partner people reach for by default, trusted because the work is dependable rather than because the pitch was good.',
    ],

    /**
     * What a prospect is promised for making contact. Every line must stay literally
     * true: these are the first commitments the business makes, and the easiest to check.
     */
    'promises' => [
        ['icon' => 'clock',           'text' => 'A reply within one business day'],
        ['icon' => 'clipboard-text',  'text' => 'A written scope you keep either way'],
        ['icon' => 'users-three',     'text' => 'Three vetted candidates, with our reservations'],
        ['icon' => 'handshake',       'text' => 'No placement fee and no long lock-in'],
    ],

    'departments' => [
        [
            'icon'  => 'users-three',
            'title' => 'General virtual assistance',
            'body'  => 'Calendar and inbox management, data entry, customer service and file organisation. The recurring operational work that fills an owner-operator day.',
        ],
        [
            'icon'  => 'chart-line-up',
            'title' => 'Digital marketing',
            'body'  => 'Ads management, search, email marketing, content production and the reporting that says which of it worked.',
        ],
        [
            'icon'  => 'film-slate',
            'title' => 'Book production and marketing',
            'body'  => 'Editing, cover design, publishing and promotion for authors, including the social and email campaigns around a launch.',
        ],
    ],
];
