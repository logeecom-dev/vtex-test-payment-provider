<?php

namespace App\Services\BusinessLogic;

use SeQura\Middleware\Service\BusinessLogic\ConfigurationService as MiddlewareConfigurationService;

class ConfigurationService extends MiddlewareConfigurationService
{
    private const INTEGRATION_NAME = 'VTEX';

    /**
     * @inheritDoc
     */
    public function getIntegrationName(): string
    {
        return self::INTEGRATION_NAME;
    }
}
