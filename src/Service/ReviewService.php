<?php

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Review;
use App\Repository\RepositoryFactory;
use App\Request\Review\CreateRequest;
use App\Task\Message\ReviewCreatedMessage;
use App\Utils\AbstractService;
use App\Utils\AppException;
use App\Utils\ErrorCode;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class ReviewService extends AbstractService
{
    private MessageBusInterface $messageBus;

    public function __construct(
        LoggerInterface $logger,
        RepositoryFactory $repositoryFactory,
        MessageBusInterface $messageBus
    ) {
        parent::__construct($logger, $repositoryFactory);
        $this->messageBus = $messageBus;
    }

    /**
     * @throws AppException
     */
    public function create(CreateRequest $request): Review
    {
        $this->logger->info(sprintf(self::LOG_MESSAGE_STARTED, $request->getRequest()->getBaseUrl()));

        $company = $this->repositoryFactory->getCompanyRepository()->findOneById($request->getCompanyId());
        if (!$company) {
            $this->logger->error(sprintf(self::LOG_MESSAGE_ERROR, $request->getCompanyId()));
            throw new AppException(
                ErrorCode::ERROR_MESSAGE_CREATE_REVIEW,
                ErrorCode::ERROR_CODE_CREATE_REVIEW,
                null,
                Response::HTTP_NOT_FOUND,
            );
        }

        $user = $this->getUser($request);
        $review = new Review(
            $request->getTitle(),
            $request->getPro(),
            $request->getContra(),
            $request->getSuggestions(),
            new Rating(
                $request->getCulture(),
                $request->getManagement(),
                $request->getWorkLifeBalance(),
                $request->getCareerDevelopment(),
            ),
            $company,
            $user,
        );

        $review = $this->repositoryFactory->getReviewRepository()->persist($review);

        $this->messageBus->dispatch(new ReviewCreatedMessage($review->getId()));

        return $review;
    }
}
