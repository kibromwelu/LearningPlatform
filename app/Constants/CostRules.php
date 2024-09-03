<?php

namespace App\Constants;

class CostRules
{
    // public const PACKAGES = [
    //     'basic' => [
    //         'monthly' => [
    //             'ETB' => 100,
    //             'USD' => 2,
    //         ],
    //         'quarterly' => [
    //             'ETB' => 270, 
    //             'USD' => 6, 
    //         ],
    //         'annually' => [
    //             'ETB' => 1000, 
    //             'USD' => 20,
    //         ],
    //         ],
        
    //     'standard' => [
    //         'monthly' => [
    //             'ETB' => 100,
    //             'USD' => 10,
    //         ],
    //         'quarterly' => [
    //             'ETB' => 270, 
    //             'USD' => 6, 
    //         ],
    //         'annually' => [
    //             'ETB' => 1000, 
    //             'USD' => 20,
    //         ],
    //     ],
    //     'premium' => [
    //         'monthly' => [
    //             'ETB' => 100,
    //             'USD' => 100,
    //         ],
    //         'quarterly' => [
    //             'ETB' => 270, 
    //             'USD' => 6, 
    //         ],
    //         'annually' => [
    //             'ETB' => 1000, 
    //             'USD' => 20,
    //         ],
    //     ],
    //     'family' => [
    //         'monthly' => [
    //             'ETB' => 100,
    //             'USD' => 100,
    //             // ['amount' => 100, 'currency' => 'ETB'],
    //             // ['amount' => 2, 'currency' => 'USD'],
    //         ],
    //         'quarterly' => [
    //             'ETB' => 270, 
    //             'USD' => 6, 
    //         ],
    //         'annually' => [
    //             'ETB' => 1000, 
    //             'USD' => 20,
    //         ],
    //     ],
    //     'enterprise' => [
    //         'monthly' => [
    //             'ETB' => 100,
    //             'USD' => 100,
    //             // ['amount' => 100, 'currency' => 'ETB'],
    //             // ['amount' => 2, 'currency' => 'USD'],
    //         ],
    //         'quarterly' => [
    //             'ETB' => 270, 
    //             'USD' => 6, 
    //         ],
    //         'annually' => [
    //             'ETB' => 1000, 
    //             'USD' => 20,
    //         ],
    //     ],
    //     'freemium' => [
    //         'monthly' => [
    //             'ETB' => 100,
    //             'USD' => 100,
    //             // ['amount' => 100, 'currency' => 'ETB'],
    //             // ['amount' => 2, 'currency' => 'USD'],
    //         ],
    //         'quarterly' => [
    //             'ETB' => 270, 
    //             'USD' => 6, 
    //         ],
    //         'annually' => [
    //             'ETB' => 1000, 
    //             'USD' => 20,
    //         ],
    //     ],
    // ];
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
            'max_courses' => INF,
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
            'max_allowed_learners' => 2,
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
    
        'enterprise' => [
            'max_allowed_learners' => 500,
            'max_courses' => 10,
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
