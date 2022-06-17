<?php

namespace App\Task;

use App\Service\ReviewAnalyticsService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

abstract class ReviewHandler implements MessageHandlerInterface
{
    public const LOG_MESSAGE_START   = 'STARTED CONSUMING';
    public const LOG_MESSAGE_FINISH  = 'FINISHED CONSUMING';

    protected ReviewAnalyticsService $service;

    protected LoggerInterface $logger;

    public function __construct(ReviewAnalyticsService $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    public function execute(callable $execute): void
    {
        $this->logger->info(self::LOG_MESSAGE_START);
        try {
            $execute();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        $this->logger->info(self::LOG_MESSAGE_FINISH);
    }
}
