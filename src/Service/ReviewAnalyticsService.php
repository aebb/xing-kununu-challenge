<?php

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Review;
use App\Task\Message\ReviewCreatedMessage;
use App\Utils\AbstractService;
use Sentiment\Analyzer;

class ReviewAnalyticsService extends AbstractService
{
    private const PRECISION = 2;

    private function findReview(ReviewCreatedMessage $message): ?Review
    {
        return $this->repositoryFactory->getReviewRepository()->findOneById($message->getReviewId());
    }

    public function calculateScore(ReviewCreatedMessage $message): Review
    {
        $review = $this->findReview($message);

        $company = $review->getCompany();
        $reviews = $company->getReviews(); //consider limit

        $score = 0;
        foreach ($company->getReviews() as $entry) {
            $score += $entry->getRating()->getScore();
        }

        $score = round(($score / count($reviews) / Rating::METRICS_COUNT), self::PRECISION) * 100 ?? 0;
        $company->getScore()->updateScore($score);

        $this->repositoryFactory->getCompanyScoreRepository()->update($company->getScore());

        return $review;
    }

    public function calculateSentiment(ReviewCreatedMessage $message): Review
    {
        $review = $this->findReview($message);
        $analyzer = new Analyzer();

        $result = $analyzer->getSentiment($review);

        $review->getSentiment()->updateSentiment(
            $result['pos'] ?? 0.0,
            $result['neg'] ?? 0.0,
            $result['neu'] ?? 0.0,
        );

        $this->repositoryFactory->getSentimentRepository()->update($review->getSentiment());

        return $review;
    }
}
