<?php

namespace App\Task;

use App\Entity\Review;
use App\Service\ReviewAnalyticsService;
use App\Task\Message\ReviewCreatedMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ReviewSentimentHandler extends ReviewHandler
{
    public function __invoke(ReviewCreatedMessage $message): void
    {
        $this->execute(fn() => $this->service->calculateSentiment($message));
    }
}
