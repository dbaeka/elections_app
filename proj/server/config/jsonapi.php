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
                'districts'
            ],
            'allowedFilters' => [
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
            'allowedIncludes' => [],
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
            ]
        ]
    ],
];
