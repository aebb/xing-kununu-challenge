<?php

namespace App\Controller;

use App\Request\Review\CreateRequest;
use App\Service\ReviewService;
use App\Utils\AbstractController;
use App\Utils\RequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    private RequestValidator $validator;

    private ReviewService $service;

    public function __construct(RequestValidator $validator, ReviewService $service)
    {
        $this->validator = $validator;
        $this->service   = $service;
    }

    /**
     * @Route("/review", name="review.create", methods={"POST"})
     */
    public function executeCreate(Request $request): Response
    {
        return $this->execute(
            fn() => $this->json(
                $this->service->create($this->validator->process(new CreateRequest($request))),
                Response::HTTP_CREATED
            )
        );
    }
}
