<?php

namespace App\Shared\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HealthCheckController extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['status' => 'Service Available'], Response::HTTP_OK, [
            'Content-Security-Policy' => "default-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'",
            'X-XSS-Protection' => "1; mode=block",
            'X-Content-Type-Options' => "nosniff",
            'X-Frame-Options' => "Deny",
            'Strict-Transport-Security' => 'max-age=31536000',
        ]);
    }
}