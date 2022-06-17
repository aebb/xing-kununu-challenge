<?php

namespace App\Utils;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractController extends SymfonyController
{
    /** @codeCoverageIgnore  */
    public function execute(callable $execute): JsonResponse
    {
        try {
            return $execute();
        } catch (AppException $appException) {
            return $this->json($appException, $appException->getStatusCode());
        } catch (Exception $exception) {
            $appException = new AppException();
            return $this->json($appException, $appException->getStatusCode());
        }
    }
}
