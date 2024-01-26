<?php

return [
    [
        'label' => 'Users',
        'icon' => 'mdi mdi-account-multiple',
        'items' => [
            [
                'label' => 'List',
                'route' => 'admin.users.index',
            ],
            [
                'label' => 'New',
                'route' => 'admin.users.edit',
            ],
        ],
    ],
    [
        'label' => 'Posts',
        'icon' => 'mdi mdi mdi-newspaper',
        'items' => [
            [
                'label' => 'Published',
                'route' => 'admin.posts.published.index',
            ],
            [
                'label' => 'Not Published',
                'route' => 'admin.posts.not-published.index',
            ],
        ],
    ],
    [
        'label' => 'Comments',
        'icon' => 'mdi mdi mdi-comment',
        'items' => [
            [
                'label' => 'List',
                'route' => 'admin.comments.index',
            ],
        ],
    ],
    [
        'label' => 'Reports',
        'icon' => 'mdi mdi-flag',
        'items' => [
            [
                'label' => 'Pending',
                'route' => 'admin.reports.index',
                'parameters' => ['type' => 'pending'],
            ],
            [
                'label' => 'Rejected',
                'route' => 'admin.reports.index',
                'parameters' => ['type' => 'rejected'],
            ],
            [
                'label' => 'Enforced',
                'route' => 'admin.reports.index',
                'parameters' => ['type' => 'enforced'],
            ],
        ],
    ],
];
