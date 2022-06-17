<?php

namespace App\Utils;

use App\Entity\User;
use App\Repository\RepositoryFactory;
use App\Request\RequestModel;
use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    public const LOG_MESSAGE_STARTED = 'STARTED REQUEST: %s';
    public const LOG_MESSAGE_ERROR   = 'ERROR REQUEST %s';

    protected LoggerInterface $logger;

    protected RepositoryFactory $repositoryFactory;

    public function __construct(LoggerInterface $logger, RepositoryFactory $repositoryFactory)
    {
        $this->logger = $logger;
        $this->repositoryFactory = $repositoryFactory;
    }

    /**
     * @throws AppException
     * @codeCoverageIgnore
     */
    protected function getUser(RequestModel $requestModel): User
    {
        $token = $requestModel->getToken();
        $user = $this->repositoryFactory->getUserRepository()->findUserByToken($requestModel->getToken());
        if (!$user) {
            $this->logger->error(self::LOG_MESSAGE_ERROR . $token);
            throw new AppException();
        }
        return $user;
    }
}
