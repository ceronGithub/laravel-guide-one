<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Log Names
    |--------------------------------------------------------------------------
    |
    */
    'LOG_NAMES' => [
        'USER_LOGIN' => "User Login". env('LOG_NAME_SUFFIX'),
        'USER_ATTEMPT' => "User Login Fail" . env('LOG_NAME_SUFFIX'),
        'USER_LOGOUT' => "User Logout" . env('LOG_NAME_SUFFIX'),

        'USER_CREATE_STORE' => "User Created Store" . env('LOG_NAME_SUFFIX'),
        'USER_UPDATED_STORE' => "User Updated Store" . env('LOG_NAME_SUFFIX'),

        'USER_CREATE_MACHINE' => "User Created Machine" . env('LOG_NAME_SUFFIX'),
        'USER_UPDATED_MACHINE' => "User Updated Machine" . env('LOG_NAME_SUFFIX'),
        'USER_DELETED_MACHINE' => "User Deleted Machine" . env('LOG_NAME_SUFFIX'),

        'USER_CREATE_MACHINE_SLOT' => "User Created Machine Slot" . env('LOG_NAME_SUFFIX'),
        'USER_UPDATED_MACHINE_SLOT' => "User Updated Machine Slot" . env('LOG_NAME_SUFFIX'),
        'USER_DELETED_MACHINE_SLOT' => "User Deleted Machine Slot" . env('LOG_NAME_SUFFIX'),

        'USER_CREATE_PRODUCT' => "User Created Product" . env('LOG_NAME_SUFFIX'),
        'USER_UPDATED_PRODUCT' => "User Updated Product" . env('LOG_NAME_SUFFIX'),
        'USER_DELETED_PRODUCT' => "User Deleted Product" . env('LOG_NAME_SUFFIX')
    ],

];
