<?php

declare(strict_types=1);

/**
 * The author-facing publishing and digital-media line.
 *
 * Same shape as services.php. Kept separate because the two audiences are sold
 * differently and the nav splits on exactly this boundary.
 *
 * Ported verbatim from the source build's lib/Content.php. Content is data, never
 * markup: views read this, they do not restate it.
 */
return [
    'hollywood-pitch' => [
        'nav' => 'Hollywood Pitch',
        'name' => 'Hollywood Producer\'s Pitch',
        'h1' => 'Put your book in front of people who option books',
        'deck' => 'Screen-adaptation treatment, pitch deck and script coverage, packaged for studios.',
        'title' => 'Hollywood Producer\'s Pitch for Authors',
        'meta' => 'Screen-adaptation packaging for authors: treatment, pitch deck and script coverage prepared to the format producers and studios actually read.',
        'answer' => 'A Hollywood pitch package prepares your book for screen adaptation conversations. It includes a screen treatment, a pitch deck and script coverage, assembled in the format producers expect, so the material can be sent to studios, directors and streaming platforms without further preparation.',
        'icon' => 'film-slate',
        'includes' => [
            'Screen-adaptation treatment written to industry format',
            'Pitch deck covering logline, comparables, audience and tone',
            'Script coverage identifying the adaptation path',
            'Guidance on who to approach and in what order',
        ],
        'faqs' => [
            [
                'Does this guarantee an adaptation?',
                'No, and anyone promising one is not being straight with you. What this does is remove the reason a producer stops reading, which is material that is not in the format they work in. The decision stays theirs.',
            ],
            [
                'Do I need a finished screenplay?',
                'Not for the pitch package. A completed manuscript is enough. A screenplay becomes relevant later, and film festival submissions do require one.',
            ],
        ],
    ],
    'book-trailers' => [
        'nav' => 'Book Trailers',
        'name' => 'Video Book Trailers',
        'h1' => 'A trailer does the work a blurb cannot',
        'deck' => 'Cinematic short-form video built for the platforms that already favour video.',
        'title' => 'Video Book Trailer Production',
        'meta' => 'Cinematic book trailers combining visuals, music and voiceover, cut for YouTube, Facebook, Instagram and TikTok, plus ads, newsletters and launch events.',
        'answer' => 'A video book trailer combines visuals, text, music and voiceover into a short cinematic preview of your book. Because social platforms rank video above static posts, a trailer reaches readers who would never open a written summary, and the same asset works in ads, newsletters and launch events.',
        'icon' => 'film-reel',
        'includes' => [
            'Scripted trailer built from your manuscript',
            'Licensed music and professional voiceover',
            'Platform cuts for YouTube, Instagram, TikTok and Facebook',
            'Versions sized for ads and for your author website',
        ],
        'faqs' => [
            [
                'How long should a book trailer be?',
                'Between 30 and 60 seconds for social, with a shorter vertical cut for Reels and TikTok. Longer trailers get made and rarely get watched to the end.',
            ],
            [
                'Can I use it in paid advertising?',
                'Yes, and it is one of the better uses. You get the platform-specific aspect ratios and durations as part of the delivery, so nothing needs recutting before a campaign.',
            ],
        ],
    ],
    'book-reviews' => [
        'nav' => 'Book Reviews',
        'name' => 'Professional Book Reviews',
        'h1' => 'Reviews are the proof a reader looks for first',
        'deck' => 'Editorial reviews that build credibility and improve how you rank on retail platforms.',
        'title' => 'Professional Book Review Service',
        'meta' => 'Professional editorial book reviews that build author credibility, improve discoverability on Amazon and Google, and give you usable feedback on the work.',
        'answer' => 'Professional book reviews give a new title the editorial credibility readers look for before buying. Reviews also feed retail and search ranking on platforms like Amazon and Google, which makes the book easier to discover, and they surface concrete feedback you can use on the next manuscript.',
        'icon' => 'star',
        'includes' => [
            'Editorial reviews written by readers in your genre',
            'Review copy suitable for retail listings and press use',
            'Constructive feedback separated from the public review',
            'Guidance on where reviews carry the most weight',
        ],
        'faqs' => [
            [
                'Are these verified purchase reviews?',
                'No. These are editorial reviews, which is a different thing from a retail customer review, and we are explicit about the distinction. Retail platforms have their own rules about incentivised reviews and we do not work around them.',
            ],
            [
                'What if a review is unfavourable?',
                'You get the feedback either way. An honest editorial read that identifies a structural problem is more valuable before a launch than after it.',
            ],
        ],
    ],
    'film-submissions' => [
        'nav' => 'Film Submissions',
        'name' => 'Film Festival Submissions',
        'h1' => 'Get submitted to festivals without learning the process',
        'deck' => 'Two festival submissions handled end to end, on the festival calendar.',
        'title' => 'Film Festival Submission Service',
        'meta' => 'Film festival submission handled for you: two submissions prepared and filed to festival requirements. Requires a screenplay, treatment, logline and genre.',
        'answer' => 'This service handles two film festival submissions on your behalf, prepared to each festival requirements and filed on their calendar. You need a completed screenplay, a treatment, a logline and a stated genre before submission. Turnaround depends on the festival schedule rather than ours.',
        'icon' => 'trophy',
        'includes' => [
            'Two film festival submissions, prepared and filed',
            'Requirement check against each festival brief',
            'Submission tracking and outcome reporting',
        ],
        'requires' => [
            'A completed screenplay',
            'A treatment',
            'A logline',
            'A specified genre',
        ],
        'faqs' => [
            [
                'How long does it take?',
                'It depends entirely on the festival calendar. Festivals open and close submissions on fixed dates and judge on their own schedule, so the timeline is theirs. We file inside the window and report the outcome.',
            ],
            [
                'What if I do not have a screenplay yet?',
                'Then festival submission is premature. Start with the Hollywood pitch package, which works from a finished manuscript.',
            ],
        ],
    ],
    'social-media-kits' => [
        'nav' => 'Author Social Kits',
        'name' => 'Author Social Media Kits',
        'h1' => 'An author platform that gets built and then run',
        'deck' => 'Page setup, twice-weekly Reels, poster ads and audience growth, then handed to you.',
        'title' => 'Author Social Media Kit and Premium',
        'meta' => 'Author social media packages: Facebook page creation, twice-weekly Reels, book poster ad management and audience growth, with full access transferred to you.',
        'answer' => 'Two author social packages. The Kit runs two months with twice-weekly Reels, poster ad management and growth to 5,000-10,000 followers from a mix of organic and seeded audience. Premium runs a full year, targets 10,000-30,000 fully organic followers, and adds ad monetisation. Both transfer all access to you at the end.',
        'icon' => 'instagram-logo',
        'tiers' => [
            'Social Media Kit' => [
                'Author Facebook page creation',
                'Reels created and posted twice weekly for two months',
                'Growth to 5,000-10,000 followers, mixed organic and seeded',
                'Book poster ad management',
                'All access transferred to you after two months',
            ],
            'Social Media Premium' => [
                'Author Facebook page creation',
                'Reels created and posted twice weekly for one year',
                'Growth to 10,000-30,000 followers, fully organic',
                'Book poster ad management and monetisation',
                'All access transferred to you after one year',
            ],
        ],
        'faqs' => [
            [
                'What does "mixed organic and seeded" mean?',
                'On the Kit tier, part of the follower count comes from paid audience seeding rather than purely organic discovery. Premium is fully organic, which is slower but produces an audience that actually engages. We state the difference plainly because it affects what the page is worth to you.',
            ],
            [
                'Do I keep the page?',
                'Yes. Full access transfers to you at the end of the term, and the page is created under your ownership from the start.',
            ],
        ],
    ],
    'author-websites' => [
        'nav' => 'Author Websites',
        'name' => 'Author Website Creation',
        'h1' => 'A website your book can point at',
        'deck' => 'Up to five custom pages built to your concept, with unlimited video hosting.',
        'title' => 'Author Website Design and Build',
        'meta' => 'Custom author website built to your concept: up to five pages, one year included, and unlimited video hosting through Vimeo for readings and trailers.',
        'answer' => 'A custom author website of up to five pages, designed to your concept rather than dropped into a template. The first year is included, renewal is 200 dollars per year, and video hosting through Vimeo is unlimited so readings, interviews and trailers can all live on the site.',
        'icon' => 'globe',
        'includes' => [
            'Custom design built to your concept',
            'Up to five pages',
            'First year included, renewal 200 dollars per year',
            'Unlimited video hosting via Vimeo',
        ],
        'faqs' => [
            [
                'Who owns the domain?',
                'You do. The site is built under your account so you keep the domain, the content and the ability to move host later. A website you cannot take with you is a rental, not an asset.',
            ],
            [
                'Can I add pages later?',
                'Yes. Five pages covers the standard author set: home, about, books, events and contact. Additional pages are quoted individually.',
            ],
        ],
    ],
];
