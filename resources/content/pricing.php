<?php

declare(strict_types=1);

/**
 * Engagement models, the factors that move a quote, and pricing FAQs.
 *
 * NO RATE NUMBERS APPEAR HERE, because none have been confirmed as publishable. Every
 * model's 'rate' is null and the template renders "Quoted against your scope" in its
 * place. Adding real pricing later is filling in 'rate', not a redesign.
 *
 * Do not invent a plausible-looking figure to fill the gap. A wrong number is worse than
 * no number: a prospect quotes it back to you on a call, and you are then either
 * honouring a price you did not set or explaining why it was never real.
 *
 * Lifted out of the pricing template during the Laravel port, where it was hardcoded.
 */
return [
    'models' => [
        [
            'name'  => 'Part time',
            'hours' => '20 hours a week',
            'rate'  => null,
            'best'  => 'A first delegation, or a role whose real shape is still emerging.',
            'notes' => [
                'One person, half your week covered',
                'Written scope and weekly reporting',
                'Move to full time whenever it makes sense',
            ],
            'recommended' => false,
        ],
        [
            'name'  => 'Full time',
            'hours' => '40 hours a week',
            'rate'  => null,
            'best'  => 'A defined role you already know is a full week of work.',
            'notes' => [
                'One person, dedicated to you, your hours',
                'Written scope and weekly reporting',
                'Named replacement guarantee',
                'Cover arranged for leave',
            ],
            'recommended' => true,
        ],
        [
            'name'  => 'Team',
            'hours' => 'Three or more people',
            'rate'  => null,
            'best'  => 'A function to move rather than a task, with a lead who runs it.',
            'notes' => [
                'A team with a coordinator who reports to you',
                'Shared process documentation across the team',
                'Consolidated weekly reporting',
                'Cover handled within the team',
            ],
            'recommended' => false,
        ],
    ],

    'drivers' => [
        ['title' => 'Seniority', 'body' => 'A closer running your pricing conversations costs more than an appointment setter working a script. Both are legitimate; they are different jobs.'],
        ['title' => 'Hours and coverage', 'body' => 'Full US business hours costs more than an overlap window. Evening or weekend cover costs more again, because it is a harder shift to staff well.'],
        ['title' => 'Specialist tooling', 'body' => 'A role that needs a specific platform at a competent level narrows the pool. Common tools do not move the number.'],
        ['title' => 'Team size', 'body' => 'Three or more people share a coordinator and one set of process documentation, which is why per-person cost falls as the team grows.'],
    ],

    'faqs' => [
        ['Why is there no price list?', 'Because the honest answer depends on the role, the hours and the seniority, and a single headline rate would be wrong for most of the people reading it. You get a written quote after the scoping call, before any commitment.'],
        ['What am I actually paying for?', 'One monthly figure covering the person, their management, their equipment and their cover. There is no separate recruitment fee, no placement fee, and no charge for replacing someone who was not right.'],
        ['Is there a minimum term?', 'No long lock-in. The engagement runs month to month after the first month, because a contract that traps you is a contract we would have to enforce instead of earning.'],
        ['What happens if I need to pause?', 'Tell us and we pause. Roles tied to seasonal work do this routinely. Pausing keeps the same person available where the gap is short.'],
        ['Do I pay for the scoping call?', 'No. You keep the written scope from it whether or not you go ahead, because it is useful on its own and it is the only way to compare us against anyone else fairly.'],
    ],
];
