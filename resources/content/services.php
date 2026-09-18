<?php

declare(strict_types=1);

/**
 * The five business-facing service lines.
 *
 * One array entry produces the service page, its nav entry, its footer link, its sitemap
 * row, its llms.txt section, its lead-form option and its Service + FAQPage schema. There
 * is no per-service template: adding a service is adding an entry here.
 *
 * Ported verbatim from the source build's lib/Content.php. Content is data, never
 * markup: views read this, they do not restate it.
 */
return [
    'sales-development' => [
        'nav' => 'Sales Development',
        'name' => 'Sales Development and Closing',
        'h1' => 'Sales support that books meetings and closes them',
        'deck' => 'Lead generators, appointment setters and closers who work your CRM and your hours.',
        'title' => 'Remote Sales Development and Closers',
        'meta' => 'Hire remote lead generators, appointment setters and closers. They work your CRM, your script and your timezone, and you keep every recording and record.',
        'answer' => 'VirtuaCore places remote sales staff in three distinct roles: lead generators who build and qualify pipeline, appointment setters who book meetings straight onto your calendar, and closers who run the call and sign the deal. Each one works inside your CRM, follows your script, and covers your business hours.',
        'icon' => 'target',
        'roles' => [
            'Lead Generator' => 'Builds and qualifies the list, runs first-touch outreach, and hands over only prospects that match your criteria.',
            'Appointment Setter' => 'Works the qualified list by phone, email and chat, handles the objection before the meeting, and books onto your calendar.',
            'Closer' => 'Runs the sales call end to end, handles pricing and objections, and brings back a signed agreement or a clear reason it did not close.',
        ],
        'outcomes' => [
            'Stop prospecting in the gaps between meetings',
            'Stop letting inbound enquiries sit overnight',
            'Stop paying a full local salary for pipeline work',
            'Stop guessing why deals stalled, because every call is logged',
        ],
        'owns' => [
            'List building and enrichment against your ideal-customer criteria',
            'First-touch and follow-up sequences across phone, email and LinkedIn',
            'CRM hygiene: every contact, stage and next action kept current',
            'Meeting confirmations and no-show recovery',
            'A weekly number you can hold them to',
        ],
        'tools' => [
            'HubSpot',
            'Salesforce',
            'Pipedrive',
            'Close',
            'Apollo',
            'ZoomInfo',
            'Outreach',
            'Aircall',
            'RingCentral',
            'LinkedIn Sales Navigator',
        ],
        'faqs' => [
            [
                'Will they work my timezone?',
                'Yes. Coverage is set to your business hours before anyone starts, and it is part of the placement brief rather than something negotiated later. Most clients run full US business-hours coverage.',
            ],
            [
                'Do they use my CRM or theirs?',
                'Yours, always. Your pipeline data stays in your system under your ownership. We never hold client pipeline in an intermediate tool, so there is nothing to migrate back if you end the engagement.',
            ],
            [
                'How long before they are productive?',
                'Plan on a structured first week: your offer, your objection handling, your CRM conventions. Setters and lead generators typically hit a steady daily number in week two. Closers take longer because they need your pricing edge cases.',
            ],
            [
                'What if the person is not right?',
                'You tell us and we replace them. The screening work is ours to get right, so a bad match is our cost to fix, not yours to absorb.',
            ],
        ],
    ],
    'administrative-support' => [
        'nav' => 'Admin Support',
        'name' => 'Executive and Administrative Support',
        'h1' => 'Get the administrative day off your desk',
        'deck' => 'Inbox, calendar, documents and follow-up, handled by someone who learns how you work.',
        'title' => 'Remote Executive and Admin Assistants',
        'meta' => 'Hire a remote executive assistant for inbox triage, calendar control, document management and follow-up. Set up in days, working your hours and your tools.',
        'answer' => 'A VirtuaCore administrative assistant takes ownership of the recurring work that fills an owner-operator day: inbox triage, calendar control, document organisation, data entry, travel booking and follow-up chasing. They work in your existing tools and report against a written scope agreed before day one.',
        'icon' => 'calendar-check',
        'roles' => [
            'Executive Assistant' => 'Runs your inbox and calendar, prepares what you need before each meeting, and chases the things you would otherwise forget.',
            'Administrative Assistant' => 'Owns recurring operational admin: data entry, document filing, order processing, supplier follow-up and reporting.',
        ],
        'outcomes' => [
            'Stop starting the day with 90 unread emails',
            'Stop being the bottleneck on scheduling',
            'Stop losing an evening a week to admin catch-up',
            'Stop paying senior hourly value for clerical work',
        ],
        'owns' => [
            'Inbox triage against rules you set, with only real decisions escalated',
            'Calendar control, including conflicts, buffers and confirmations',
            'Document organisation and a filing structure that survives handover',
            'Data entry, expense capture and recurring reporting',
            'Travel, supplier and appointment coordination',
        ],
        'tools' => [
            'Google Workspace',
            'Microsoft 365',
            'Outlook',
            'Slack',
            'Notion',
            'Airtable',
            'Dropbox',
            'Calendly',
            'Expensify',
            'DocuSign',
        ],
        'faqs' => [
            [
                'How much access do I have to give?',
                'As little as the work needs. Most start with delegated inbox and calendar access rather than a shared password, which you can revoke instantly and which leaves an audit trail. We will not ask for access to banking or payroll systems.',
            ],
            [
                'Can they start part time?',
                'Yes. Part-time is often the right start for admin support because the scope becomes clearer after a few weeks of real work. Moving to full time later is a simple change.',
            ],
            [
                'What happens when they take leave?',
                'Coverage is agreed as part of the engagement rather than improvised. Because the filing and process documentation is a deliverable, a stand-in can pick up the work without you re-explaining it.',
            ],
        ],
    ],
    'customer-support' => [
        'nav' => 'Customer Support',
        'name' => 'Customer Support',
        'h1' => 'Answer every customer, not just the ones in office hours',
        'deck' => 'Email, chat, phone and social handled to your tone and your service targets.',
        'title' => 'Remote Customer Support Agents',
        'meta' => 'Hire remote customer support agents for email, live chat, phone and social. Extend your coverage hours without adding local headcount or a new office.',
        'answer' => 'VirtuaCore customer support agents handle inbound enquiries across email, live chat, phone and social channels inside your existing helpdesk. Coverage hours, response-time targets and escalation rules are agreed before anyone starts, so you extend service hours without changing how your team works.',
        'icon' => 'headset',
        'roles' => [
            'Customer Support Agent' => 'Handles inbound tickets, chat and calls to your response targets, escalating on rules you define rather than on guesswork.',
            'Customer Success Coordinator' => 'Runs proactive follow-up: onboarding check-ins, renewal reminders and the outreach that stops churn before it starts.',
        ],
        'outcomes' => [
            'Stop letting overnight tickets age into complaints',
            'Stop pulling your operators off billable work to answer chat',
            'Stop losing customers to a slow first response',
            'Stop capping your service hours at your local office hours',
        ],
        'owns' => [
            'First response inside the target you set',
            'Ticket resolution and clean handover on anything escalated',
            'Live chat and social replies in your brand voice',
            'A maintained macro and knowledge-base library',
            'Weekly reporting on volume, response time and recurring causes',
        ],
        'tools' => [
            'Zendesk',
            'Intercom',
            'Freshdesk',
            'HubSpot Service Hub',
            'Gorgias',
            'Help Scout',
            'Front',
            'Twilio',
            'Shopify',
            'Salesforce Service Cloud',
        ],
        'faqs' => [
            [
                'Will customers know they are talking to an outsourced agent?',
                'Only if you want them to. Agents work under your brand, in your helpdesk, using your tone guide. Most clients present them as part of the team, because operationally they are.',
            ],
            [
                'Can you cover nights and weekends?',
                'Yes, and this is the most common reason clients start. Because the team is remote and distributed, extending into evenings or weekends is a scheduling decision rather than a facilities one.',
            ],
            [
                'How do you keep quality consistent?',
                'A written tone guide and escalation matrix, macros reviewed rather than improvised, and weekly ticket sampling. The reporting shows you recurring causes, not just volume, so support work feeds back into fixing the product.',
            ],
        ],
    ],
    'project-management' => [
        'nav' => 'Project Management',
        'name' => 'Project Management',
        'h1' => 'Someone whose job is making the deadline real',
        'deck' => 'Timelines, dependencies and the follow-up that keeps work moving without you chasing.',
        'title' => 'Remote Project Managers and Coordinators',
        'meta' => 'Hire a remote project manager or coordinator to own timelines, dependencies, status reporting and the daily follow-up that keeps delivery on schedule.',
        'answer' => 'A VirtuaCore project manager owns the plan and the follow-up: task breakdown, dependencies, deadlines, status reporting and chasing the blockers. They work in your project tool and give you one accountable person for whether the work lands on time, instead of the work landing on you.',
        'icon' => 'kanban',
        'roles' => [
            'Project Coordinator' => 'Keeps the board accurate, chases blockers daily, and produces the status update so nobody has to assemble it.',
            'Project Manager' => 'Owns scope, timeline and dependencies across a workstream, and flags slippage while it is still recoverable.',
        ],
        'outcomes' => [
            'Stop finding out a deadline slipped on the day it slipped',
            'Stop being the only person who knows the full plan',
            'Stop assembling the status report yourself on Friday',
            'Stop losing handoffs between contractors and staff',
        ],
        'owns' => [
            'Task breakdown, sequencing and realistic dates',
            'A board that reflects reality, updated daily',
            'Blocker identification and the chase to clear it',
            'Status reporting your clients or board can read without translation',
            'Documented process, so the next project starts faster than the last',
        ],
        'tools' => [
            'Asana',
            'Monday.com',
            'ClickUp',
            'Jira',
            'Trello',
            'Notion',
            'Smartsheet',
            'Basecamp',
            'Linear',
            'Microsoft Project',
        ],
        'faqs' => [
            [
                'Can they manage my in-house team?',
                'They can coordinate your team without managing them as employees. In practice they own the plan, the tracking and the follow-up, while line management and performance stay with you. That split works because the friction is usually coordination, not authority.',
            ],
            [
                'Do they need to be in my timezone?',
                'For coordination work, meaningful overlap matters more than a full match. Four hours of overlap covers standups and blocker clearing. We set the overlap window before placement.',
            ],
            [
                'What if my process is a mess right now?',
                'That is a common starting point and it is fine. Expect the first two weeks to be documenting what actually happens before improving it. A plan built on the real process holds; one built on the intended process does not.',
            ],
        ],
    ],
    'social-media-marketing' => [
        'nav' => 'Social and Marketing',
        'name' => 'Social Media and Marketing',
        'h1' => 'Publish consistently without it landing on you',
        'deck' => 'Content, scheduling, community replies and campaign support on a calendar you approve.',
        'title' => 'Remote Social Media and Marketing Assistants',
        'meta' => 'Hire remote social media and marketing assistants for content creation, scheduling, community management and email campaign support on an approved calendar.',
        'answer' => 'VirtuaCore social and marketing assistants run the publishing operation: content drafting, scheduling, community replies, and email campaign build and send. You approve a calendar in advance, they execute it, and you get monthly reporting on reach, engagement and what to repeat.',
        'icon' => 'megaphone-simple',
        'roles' => [
            'Social Media Assistant' => 'Drafts and schedules content, replies to comments and messages, and keeps the calendar full a month ahead.',
            'Marketing Assistant' => 'Builds and sends email campaigns, maintains lists and segments, and assembles the reporting.',
            'Content Creator' => 'Produces the graphics, short-form video edits and captions that the calendar needs.',
        ],
        'outcomes' => [
            'Stop going quiet on social for three weeks at a time',
            'Stop writing captions at 11pm',
            'Stop leaving comments and DMs unanswered',
            'Stop sending the newsletter whenever you remember',
        ],
        'owns' => [
            'A content calendar filled and approved a month ahead',
            'Drafting, scheduling and publishing across your channels',
            'Community management: comments, mentions and direct messages',
            'Email campaign build, list hygiene and send',
            'Monthly reporting tied to what you asked the content to do',
        ],
        'tools' => [
            'Meta Business Suite',
            'Buffer',
            'Later',
            'Hootsuite',
            'Canva',
            'CapCut',
            'Adobe Express',
            'Mailchimp',
            'Klaviyo',
            'HubSpot Marketing',
        ],
        'faqs' => [
            [
                'Do they write in my voice?',
                'They work from a tone guide built with you in the first week, then everything goes through your approval until the voice is reliably right. Most clients move to spot-checking rather than full approval after about a month.',
            ],
            [
                'Can they run paid ads?',
                'They can build, schedule and report on campaigns. Whether they own budget decisions depends on the person and the spend level, and we are direct about that during placement rather than after.',
            ],
            [
                'What if I have no brand assets yet?',
                'Then the first deliverable is a basic kit: templates, palette, type and a caption structure. Publishing consistently on a simple system beats waiting for a full brand refresh.',
            ],
        ],
    ],
];
