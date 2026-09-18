<?php

declare(strict_types=1);

/**
 * The four artefacts produced in a placement's first week.
 *
 * Lifted out of the how-it-works template during the Laravel port: it was hardcoded
 * markup there, which breaks the rule that content is data. The page renders this; it
 * does not restate it.
 */
return [
    [
        'icon'  => 'shield-check',
        'title' => 'Access, least privilege',
        'body'  => 'Delegated rather than shared. Revocable in one click, and every action leaves an audit trail against their own identity.',
    ],
    [
        'icon'  => 'megaphone-simple',
        'title' => 'A tone guide',
        'body'  => 'How you sound to customers, written down with real examples of yes and no, so it can be checked rather than guessed.',
    ],
    [
        'icon'  => 'kanban',
        'title' => 'An escalation matrix',
        'body'  => 'What they decide, what they flag, and what stops and waits for you. Ambiguity here is what produces either paralysis or overreach.',
    ],
    [
        'icon'  => 'clipboard-text',
        'title' => 'Process documentation',
        'body'  => 'The work as it actually happens, written as they learn it. This is what lets a stand-in cover leave without you re-explaining anything.',
    ],
];
