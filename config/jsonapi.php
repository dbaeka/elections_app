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
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string'
                ]
            ],
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
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string'
                ]
            ],
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
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string'
                ]
            ],
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
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string'
                ]
            ],
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
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string'
                ]
            ],
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
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string'
                ]
            ],
        ],
        'users' => [
            'allowedSorts' => [],
            'allowedIncludes' => [
                'stations'
            ],
            'allowedFilters' => [
                AllowedFilter::exact('role'),
                AllowedFilter::partial('phone'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
            ],
            'validationRules' => [
                'update' => []
            ],
            'relationships' => [
                [
                    'type' => 'stations',
                    'method' => 'stations',
                ]
            ]
        ]
    ],
];
