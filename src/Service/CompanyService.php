<?php

namespace App\Service;

use App\Repository\RepositoryFactory;
use App\Request\Company\CompanyDTO;
use App\Request\Company\ListRequest;
use App\Utils\AbstractService;
use Psr\Log\LoggerInterface;

class CompanyService extends AbstractService
{
    private int $limit;

    public function __construct(LoggerInterface $logger, RepositoryFactory $repositoryFactory, int $listLimit)
    {
        parent::__construct($logger, $repositoryFactory);
        $this->limit = $listLimit;
    }

    public function listBest(ListRequest $request): CompanyDTO
    {
        $this->logger->info(sprintf(self::LOG_MESSAGE_STARTED, $request->getRequest()->getBaseUrl()));

        $start   = $request->getStart() ?? 0;
        $count    = min($request->getCount() ?? $this->limit, $this->limit);

        $result = $this->repositoryFactory->getCompanyRepository()->listBest(
            $request->getSearch() ?? '',
            $start,
            $count
        );

        return new CompanyDTO($result);
    }
}
