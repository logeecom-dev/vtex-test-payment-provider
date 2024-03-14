<?php

namespace App;

use App\Services\BusinessLogic\ConfigurationService;
use App\Services\BusinessLogic\OrderTransformerService;
use SeQura\Core\BusinessLogic\Domain\Multistore\StoreContext;
use SeQura\Core\Infrastructure\Configuration\Configuration;
use SeQura\Core\Infrastructure\Configuration\ConfigurationManager;
use SeQura\Core\Infrastructure\Http\CurlHttpClient;
use SeQura\Core\Infrastructure\Http\HttpClient;
use SeQura\Core\Infrastructure\Http\LoggingHttpclient;
use SeQura\Core\Infrastructure\Logger\Interfaces\ShopLoggerAdapter;
use SeQura\Core\Infrastructure\ServiceRegister;
use SeQura\Core\Infrastructure\TaskExecution\Events\QueueItemStateTransitionEventBus;
use SeQura\Core\Infrastructure\Utility\TimeProvider;
use SeQura\Middleware\BootstrapComponent as MiddlewareBootstrap;
use SeQura\Middleware\Service\Infrastructure\LoggerService;

class Bootstrap extends MiddlewareBootstrap
{
    /**
     * @inheritDoc
     */
    protected static function initServices(): void
    {
        ServiceRegister::registerService(
            HttpClient::CLASS_NAME,
            function () {
                return new LoggingHttpclient(new CurlHttpClient());
            }
        );

        ServiceRegister::registerService(StoreContext::class, static function () {
            return StoreContext::getInstance();
        });

        ServiceRegister::registerService(
            QueueItemStateTransitionEventBus::CLASS_NAME,
            function () {
                return QueueItemStateTransitionEventBus::getInstance();
            }
        );


        ServiceRegister::registerService(
            OrderTransformerService::class,
            static function() {
                return OrderTransformerService::getInstance();
            }
        );

        ServiceRegister::registerService(
            ShopLoggerAdapter::class,
            static function () {
                return LoggerService::getInstance();
            }
        );

        ServiceRegister::registerService(
            TimeProvider::CLASS_NAME,
            function () {
                return TimeProvider::getInstance();
            }
        );

        ServiceRegister::registerService(
            Configuration::class,
            static function () {
                return ConfigurationService::getInstance();
            }
        );

        ServiceRegister::registerService(
            ConfigurationManager::CLASS_NAME,
            function () {
                return ConfigurationManager::getInstance();
            }
        );
    }
}
