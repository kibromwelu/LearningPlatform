<?php
namespace App\Constants;

class CostRules
{
    public const PACKAGES = [
        'basic' => [
            'max_allowed_learners' => 1,
            'max_courses' => 5,
            'pricing' => [
                'monthly' => [
                    'ETB' => 100,
                    'USD' => 2,
                ],
                'quarterly' => [
                    'ETB' => 270,
                    'USD' => 6,
                ],
                'annually' => [
                    'ETB' => 1000,
                    'USD' => 20,
                ],
            ],
        ],
        'standard' => [
            'max_allowed_learners' => 1,
            'max_courses' => 10,
            'pricing' => [
                'monthly' => [
                    'ETB' => 100,
                    'USD' => 10,
                ],
                'quarterly' => [
                    'ETB' => 270,
                    'USD' => 6,
                ],
                'annually' => [
                    'ETB' => 1000,
                    'USD' => 20,
                ],
            ],
        ],
        'premium' => [
            'max_allowed_learners' => 1,
            'max_courses' => 100,
            'pricing' => [
                'monthly' => [
                    'ETB' => 100,
                    'USD' => 100,
                ],
                'quarterly' => [
                    'ETB' => 270,
                    'USD' => 6,
                ],
                'annually' => [
                    'ETB' => 1000,
                    'USD' => 20,
                ],
            ],
        ],
        'family' => [
            'max_allowed_learners' => 3,
            'max_courses' => 5,
            'pricing' => [
                'monthly' => [
                    'ETB' => 100,
                    'USD' => 100,
                ],
                'quarterly' => [
                    'ETB' => 270,
                    'USD' => 6,
                ],
                'annually' => [
                    'ETB' => 1000,
                    'USD' => 20,
                ],
            ],
        ],
        'enterprise' => [
            '1-10' => [
                'max_allowed_learners' => 10,
                'max_courses' => 5,
                'pricing' => [
                    'monthly' => [
                        'ETB' => 1000,
                        'USD' => 10,
                    ],
                    'quarterly' => [
                        'ETB' => 2500,
                        'USD' => 25,
                    ],
                    'annually' => [
                        'ETB' => 9000,
                        'USD' => 90,
                    ],
                ],
            ],
            '11-30' => [
                'max_allowed_learners' => 30,
                'max_courses' => 5,
                'pricing' => [
                    'monthly' => [
                        'ETB' => 2000,
                        'USD' => 20,
                    ],
                    'quarterly' => [
                        'ETB' => 5000,
                        'USD' => 50,
                    ],
                    'annually' => [
                        'ETB' => 18000,
                        'USD' => 180,
                    ],
                ],
            ],
            '31-50' => [
                'max_allowed_learners' => 50,
                'max_courses' => 5,
                'pricing' => [
                    'monthly' => [
                        'ETB' => 3000,
                        'USD' => 30,
                    ],
                    'quarterly' => [
                        'ETB' => 7500,
                        'USD' => 75,
                    ],
                    'annually' => [
                        'ETB' => 25000,
                        'USD' => 250,
                    ],
                ],
            ],
            '51+' => [
                'max_allowed_learners' => 100, // Example upper limit
                'max_courses' => 5,
                'pricing' => [
                    'monthly' => [
                        'ETB' => 4000,
                        'USD' => 40,
                    ],
                    'quarterly' => [
                        'ETB' => 10000,
                        'USD' => 100,
                    ],
                    'annually' => [
                        'ETB' => 35000,
                        'USD' => 350,
                    ],
                ],
            ],
        ],
        'freemium' => [
            'max_allowed_learners' => 1,
            'max_courses' => 2,
            'pricing' => [
                'monthly' => [
                    'ETB' => 100,
                    'USD' => 100,
                ],
                'quarterly' => [
                    'ETB' => 270,
                    'USD' => 6,
                ],
                'annually' => [
                    'ETB' => 1000,
                    'USD' => 20,
                ],
            ],
        ],
    ];
}
