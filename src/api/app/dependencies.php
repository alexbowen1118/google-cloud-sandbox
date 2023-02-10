<?php
declare(strict_types=1);

use DPR\API\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use DPR\API\Domain\Ubidots\UbidotsAPI;
use DPR\API\Domain\AWSAPI;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use DPR\API\Infrastructure\Persistence\DBPool;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        DBPool::class => function(ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $dbSettings = $settings->get('database');
            return new DBPool($dbSettings);
        },
        UbidotsAPI::class => function(ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $ubidotsSettings = $settings->get('ubidots');
            $dir = $settings->get('legacy_data_path');
            return new UbidotsAPI($ubidotsSettings, $dir);
        },
        AWSAPI::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $awsSettings = $settings->get('aws');
            return new AWSAPI($awsSettings);
        }
    ]);
};
