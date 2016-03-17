<?php

return [
    'backup' => [
        'name' => env('APP_URL'),
        'source' => [
            'files' => [
                'include' => [],
                'exclude' => [
                    base_path('vendor'),
                    storage_path(),
                ],
            ],
            'databases' => [
                'mysql'
            ],
        ],
        'destination' => [
            'disks' => [
                'local',
                's3'
            ],
        ],
    ],
    'cleanup' => [
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,
        'defaultStrategy' => [
            'keepAllBackupsForDays' => 7,
            'keepDailyBackupsForDays' => 16,
            'keepWeeklyBackupsForWeeks' => 8,
            'keepMonthlyBackupsForMonths' => 4,
            'keepYearlyBackupsForYears' => 2,
            'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000
        ]
    ],
    'monitorBackups' => [
        [
            'name' => env('APP_URL'),
            'disks' => ['local', 's3'],
            'newestBackupsShouldNotBeOlderThanDays' => 1,
            'storageUsedMayNotBeHigherThanMegabytes' => 5000,
        ],
    ],
    'notifications' => [
        'handler' => Spatie\Backup\Notifications\Notifier::class,
        'events' => [
            'whenBackupWasSuccessful'     => ['log', 'slack'],
            'whenCleanupWasSuccessful'    => ['log', 'slack'],
            'whenHealthyBackupWasFound'   => ['log', 'slack'],
            'whenBackupHasFailed'         => ['log', 'mail', 'slack'],
            'whenCleanupHasFailed'        => ['log', 'mail', 'slack'],
            'whenUnHealthyBackupWasFound' => ['log', 'mail', 'slack']
        ],
        'mail' => [
            'from' => 'backups@starter.io',
            'to'   => 'hello@example.com',
        ],
        'slack' => [
            'channel'  => env('SLACK_ERROR_CHANNEL'),
            'username' => env('SLACK_BOT_NAME', 'drivelog-bot'),
            'icon'     => env('SLACK_BOT_EMOJI' ,':mushroom:'),
        ],
    ]
];
