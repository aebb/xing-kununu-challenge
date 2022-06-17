<?php

namespace App\Controller;

use App\Request\Company\ListRequest;
use App\Service\CompanyService;
use App\Utils\AbstractController;
use App\Utils\RequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    private RequestValidator $validator;

    private CompanyService $service;

    public function __construct(RequestValidator $validator, CompanyService $service)
    {
        $this->validator = $validator;
        $this->service   = $service;
    }

    /**
     * @Route("/company/best", name="company.list.best", methods={"GET"})
     */
    public function executeList(Request $request): Response
    {
        return $this->execute(
            fn() => $this->json(
                $this->service->listBest($this->validator->process(new ListRequest($request))),
                Response::HTTP_OK
            )
        );
    }
}
