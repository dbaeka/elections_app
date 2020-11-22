<?php

use Spatie\QueryBuilder\AllowedFilter;

return [
    'resources' => [
        'districts' => [
            'allowedSorts' => [
                'name', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'regions', 'constituencies'
            ],
            'allowedFilters' => [
                AllowedFilter::partial('name'),
            ],
            'relationships' => [
                [
                    'type' => 'regions',
                    'method' => 'regions',
                ],
                [
                    'type' => 'constituencies',
                    'method' => 'constituencies',
                ]
            ],
            'validationRules' => [],
        ],
        'constituencies' => [
            'allowedSorts' => [
                'name', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'districts', 'stations'
            ],
            'allowedFilters' => [
                AllowedFilter::partial('name'),
            ],
            'relationships' => [
                [
                    'type' => 'districts',
                    'method' => 'districts',
                ],
                [
                    'type' => 'stations',
                    'method' => 'stations',
                ],
            ],
            'validationRules' => [],
        ],
        'results' => [
            'allowedSorts' => [
                'is_approved', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'users', 'images',
            ],
            'allowedFilters' => [
                AllowedFilter::exact('is_approved'),
            ],
            'relationships' => [
                [
                    'type' => 'users',
                    'method' => 'users',
                ],
                [
                    'type' => 'images',
                    'method' => 'images',
                ],
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.records' => 'required|array',
                ],
                'update' => [
                    'data.attributes.records' => 'sometimes|required|array'
                ]
            ],
        ],
        'images' => [
            'allowedSorts' => [
                'name', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'results',
            ],
            'allowedFilters' => [
                AllowedFilter::partial('name'),
            ],
            'relationships' => [
                [
                    'type' => 'results',
                    'method' => 'results',
                ],
            ],
            'validationRules' => [],
        ],
        'parties' => [
            'allowedSorts' => [
                'name', 'short_name', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'candidates'
            ],
            'allowedFilters' => [
                AllowedFilter::partial('name'),
                AllowedFilter::partial('short_name'),
            ],
            'relationships' => [
                [
                    'type' => 'candidates',
                    'method' => 'candidates',
                ]
            ],
            'validationRules' => [],
        ],
        'stations' => [
            'allowedSorts' => [
                'name', 'code', 'num_voters', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'users', 'constituencies'
            ],
            'allowedFilters' => [
                AllowedFilter::partial('name'),
                AllowedFilter::partial('code'),
            ],
            'relationships' => [
                [
                    'type' => 'constituencies',
                    'method' => 'constituencies',
                ],
                [
                    'type' => 'users',
                    'method' => 'users',
                ]
            ],
            'validationRules' => [],
        ],
        'candidates' => [
            'allowedSorts' => [
                'pres', 'vice', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'parties'
            ],
            'allowedFilters' => [
                AllowedFilter::partial('pres'),
                AllowedFilter::partial('vice'),
            ],
            'relationships' => [
                [
                    'type' => 'parties',
                    'method' => 'parties',
                ]
            ],
            'validationRules' => [],
        ],
        'regions' => [
            'allowedSorts' => [
                'name', 'capital', 'created_at', 'updated_at',
            ],
            'allowedIncludes' => [
                'districts'
            ],
            'allowedFilters' => [
                AllowedFilter::partial('capital'),
                AllowedFilter::partial('name'),
            ],
            'relationships' => [
                [
                    'type' => 'districts',
                    'method' => 'districts',
                ]
            ],
            'validationRules' => [],
        ],
        'users' => [
            'allowedSorts' => ['name', 'email', 'is_active', 'role', 'phone'],
            'allowedIncludes' => [
                'stations', 'results'
            ],
            'allowedFilters' => [
                AllowedFilter::exact('role'),
                AllowedFilter::partial('phone'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.phone' => 'required|string',
                    'data.attributes.password' => 'required|string',
                    'data.attributes.role' => 'required|string|in:polling,engine,display,admin',
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.phone' => 'sometimes|required|string',
                    'data.attributes.password' => 'sometimes|required|string',
                    'data.attributes.role' => 'sometimes|required|string|in:polling,engine,display,admin',
                    'data.attributes.name' => 'sometimes|required|string',
                ]
            ],
            'relationships' => [
                [
                    'type' => 'stations',
                    'method' => 'stations',
                ],
                [
                    'type' => 'results',
                    'method' => 'results',
                ]
            ]
        ]
    ],
];
