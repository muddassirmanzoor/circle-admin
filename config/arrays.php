<?php

/**
 * @see https://www.dotwconnect.com/interface/en/documentation
 */
return [
    'status' => [
        '1' => 'Active',
        '0' => 'Inactive',
    ],
    'filePath' => [
        'category_path' => public_path('uploads/categories/'),
        'sub_category_path' => public_path('uploads/sub_categories/')
    ],
    'defaultImage' => public_path('uploads/default.jpg'),
    "withdraw_schedule" => [
        0   => 'One Week',
        1   => 'Two Week',
        2   => 'One Month',
    ],
    "withdraw_statuses" => [
        'in_progress'   => [
            'text' => 'In Progess',
            'color' => 'info'
        ],
        'complete'   => [
            'text' => 'Completed',
            'color' => 'success'
        ],
    ],
    "transaction_statuses" => [
        'pending'   => [
            'text' => 'Pending',
            'color' => 'warning'
        ],
        'refunded'   => [
            'text' => 'Refunded',
            'color' => 'primary'
        ],
        'canceled'   => [
            'text' => 'Cancel',
            'color' => 'danger'
        ],
        'failed'   => [
            'text' => 'Failed',
            'color' => 'danger'
        ],
        'rejected'   => [
            'text' => 'Rejected',
            'color' => 'danger'
        ],
        'succeeded'   => [
            'text' => 'Success',
            'color' => 'success'
        ],
        'cancelled'   => [
            'text' => 'Cancelled',
            'color' => 'danger'
        ],
        'completed'   => [
            'text' => 'Completed',
            'color' => 'success'
        ],
    ],
    "phone_status" => [
        'verified'   => [
            'text' => 'Verified',
            'color' => 'success'
        ],
        'not_verified'   => [
            'text' => 'Not Verified',
            'color' => 'danger'
        ],
    ],
    "appointment_type" => [
        'pending' => [
            'confirmed' => [
                'text' => 'Confirm',
                'value' => 'confirmed',
            ],
            'cancelled' => [
                'text' => 'Cancel',
                'value' => 'cancelled',
            ],
        ],
        'confirmed' => [
            'completed' => [
                'text' => 'Complete',
                'value' => 'completed',
            ],
            'cancelled' => [
                'text' => 'Cancel',
                'value' => 'cancelled',
            ],
        ],
    ],
];
