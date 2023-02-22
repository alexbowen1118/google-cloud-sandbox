<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use DPR\API\Application\Settings\Settings;
use DPR\API\Application\Settings\SettingsInterface;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError' => true,
                'logErrorDetails' => true,
                'logger' => [
                    'name' => 'DPR-API',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'legacy_data_path' => '/legacy_device_data',
                'database' => [
                    'engine' => getenv('DB_ENGINE') ?? null,
                    'host' => getenv('DB_HOST') ?? null,
                    'port' => getenv('DB_PORT') ?? null,
                    'dbname' => getenv('DB_NAME') ?? null,
                    'user' => getenv('DB_USER') ?? null,
                    'password' => getenv('DB_PASSWORD') ?? null,
                    'charset' => getenv('DB_CHARSET') ?? null,
                ],
                'ubidots' => [
                    'apikey' => getenv('UBIDOTS_API_KEY') ?? '',
                    'datasources_url' => getenv('UBIDOTS_DATASOURCES_URL') ?? ''
                ],
                'gcloud' => [
                    'project_id' => getenv('GOOGLE_CLOUD_PROJECT') ?? '',
                    'json_keyfile' => getenv('GOOGLE_APPLICATION_CREDENTIALS') ?? ''
                ]
            ]);
        }
    ]);
};
