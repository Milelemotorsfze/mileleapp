<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Department Email Addresses
    |--------------------------------------------------------------------------
    |
    | This file contains the email addresses for different departments
    | that should receive vehicle estimation date reminders.
    |
    */

    'estimation_reminders' => [
        'demand_planning' => [
            env('DP_TEAM_EMAIL', 'team.dp@milele.com'),
        ],
        'sales_support' => [
            env('SALESUPPORT_TEAM_EMAIL', 'team.salesupport@milele.com'),
        ],
        'logistics' => [
            env('LOGISTICS_TEAM_EMAIL', 'team.logistics@milele.com'),
        ],
        'warehouse_team' => [
            env('WAREHOUSE_TEAM_EMAIL', 'team.warehouse@milele.com'),
        ],
        'qc_team' => [
            env('QC_TEAM_EMAIL', 'team.qc@milele.com'),
        ],
        'developer' => [
            env('DEVELOPER_EMAIL', 'basharat.ali@milele.com'),
        ],
    ],

    'price_update_notifications' => [
        'marketing' => [
            env('MARKETING_TEAM_EMAIL', 'team.marketing@milele.com'),
        ],
        'operations' => [
            env('OPERATIONS_TEAM_EMAIL', 'team.operations@milele.com'),
        ],
        'demand_planning' => [
            env('DP_TEAM_EMAIL', 'team.dp@milele.com'),
        ],
        'sales' => [
            env('SALES_TEAM_EMAIL', 'team.sales@milele.com'),
        ],
        'developer' => [
            env('DEVELOPER_EMAIL', 'basharat.ali@milele.com'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reminder Settings
    |--------------------------------------------------------------------------
    |
    | Configure when and how often reminders should be sent
    |
    */

    'reminder_settings' => [
        'check_days_ahead' => 5, // Check vehicles within 5 days
        'send_time' => '09:00', // Send reminders at 9 AM
        'timezone' => env('APP_TIMEZONE', 'UTC'),
        'enable_logging' => env('ESTIMATION_REMINDER_LOGGING', true),
    ],
];
