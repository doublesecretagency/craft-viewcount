module.exports = {
    markdown: {
        anchor: { level: [2, 3] },
        extendMarkdown(md) {
            let markup = require('vuepress-theme-craftdocs/markup');
            md.use(markup);
        },
    },
    base: '/view-count/',
    title: 'View Count plugin for Craft CMS',
    plugins: [
        [
            'vuepress-plugin-clean-urls',
            {
                normalSuffix: '/',
                indexSuffix: '/',
                notFoundPath: '/404.html',
            },
        ],
    ],
    theme: 'craftdocs',
    themeConfig: {
        codeLanguages: {
            php: 'PHP',
            twig: 'Twig',
            js: 'JavaScript',
        },
        logo: '/images/icon.svg',
        searchMaxSuggestions: 10,
        nav: [
            {text: 'Getting Started️', link: '/getting-started/'},
            {
                text: 'How It Works',
                items: [
                    {text: 'How to increment the counter', link: '/how-to-increment-the-counter/'},
                    {text: 'How to decrement the counter', link: '/how-to-decrement-the-counter/'},
                    {text: 'Setting or resetting a counter', link: '/setting-or-resetting-a-counter/'},
                    {text: 'Using a unique key', link: '/using-a-unique-key/'},
                    {text: 'Sort by most viewed', link: '/sort-by-most-viewed/'},
                    {text: 'Get view totals per Element', link: '/get-view-totals-per-element/'},
                    {text: 'Get view totals per User', link: '/get-view-totals-per-user/'},
                    {text: '"Total Views" fieldtype', link: '/total-views-fieldtype/'},
                    {text: 'Detailed view log', link: '/detailed-view-log/'},
                    {text: 'Events', link: '/events/'},
                ]
            },
            {
                text: 'More',
                items: [
                    {text: 'Double Secret Agency', link: 'https://www.doublesecretagency.com/plugins'},
                    {text: 'Our other Craft plugins', link: 'https://plugins.doublesecretagency.com', target:'_self'},
                ]
            },
        ],
        sidebar: {
            '/': [
                'getting-started',
                'how-to-increment-the-counter',
                'how-to-decrement-the-counter',
                'setting-or-resetting-a-counter',
                'using-a-unique-key',
                'sort-by-most-viewed',
                'get-view-totals-per-element',
                'get-view-totals-per-user',
                'total-views-fieldtype',
                'detailed-view-log',
                'events',
            ],
        }
    }
};
