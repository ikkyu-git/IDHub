<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;

class CustomizeJsonFormatter
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof AbstractProcessingHandler) {
                $handler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, true, false));
            }
        }
    }
}
